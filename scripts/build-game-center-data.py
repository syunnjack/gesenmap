"""チェーン各社の公式サイトから、ゲームセンターの実データを組み立てる。

出典（いずれも各社の公式サイト。robots.txt で許可されていることを確認して読む）:
  - GiGO お店情報サイト https://www.gigo.co.jp/shops
  - バンダイナムコ アミューズメント https://bandainamco-am.co.jp/game_center/
  - イオンファンタジー 店舗検索 https://www.fantasy.co.jp/shoplist/（ゲーム機を置くブランドのみ）
  - タイトー 店舗検索 https://www.taito.co.jp/store（店舗一覧を返すAPIを1回だけ呼ぶ）

取るのは公式ページに載っている事実だけ（店名・住所・電話・営業時間・設置ゲームの種類）。
書いていないことは埋めない。緯度経度が公表されていない店舗は、
国土地理院の住所検索APIで住所から求める。

使い方: python scripts/build-game-center-data.py
  → database/data/game-centers.json を書き出す。
  途中結果は scripts/.cache に残るので、再実行時は取得済みの分を読み直す。
"""
import json
import re
import time
import urllib.parse
import urllib.request
from datetime import date
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
CACHE = ROOT / 'scripts' / '.cache'
OUTPUT = ROOT / 'database' / 'data' / 'game-centers.json'

UA = ('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 '
      '(KHTML, like Gecko) Chrome/131.0 Safari/537.36')
DELAY = 1.5          # 公式サイトへの間隔（秒）
GEOCODE_DELAY = 1.0  # 国土地理院APIへの間隔（秒）

GIGO_SITEMAP = 'https://www.gigo.co.jp/sitemap.xml'
NAMCO_SITEMAP = 'https://bandainamco-am.co.jp/sitemap_game_center.xml'
NAMCO_SHOP = 'https://bandainamco-am.co.jp/game_center/loc/{}/'
AEON_LIST = 'https://www.fantasy.co.jp/shoplist/page/{}'
# タイトーの店舗検索が使っているAPI。1回で全店舗が返るので、robots.txt の
# Crawl-delay 20 を守っても負担にならない。
TAITO_API = ('https://www.taito.co.jp/api/LanguageStoreSearch/'
             '?stateCode=&groupID=&lang=ja&ignore=false&isGlobalOnly=false')
TAITO_STORE = 'https://www.taito.co.jp/store/{}'
TAITO_REFERER = 'https://www.taito.co.jp/store'
# イオンファンタジーはゲームセンター以外（屋内遊戯場・スイミング等）も運営している。
# ゲーム機を置くブランドだけを載せる。
AEON_BRANDS = {
    'molly': 'モーリーファンタジー',
    'mollyf': 'モーリーファンタジーf',
    'palo': 'PALO',
    'tsp': 'TOYS SPOT PALO',
    'psp': 'PRIZE SPOT PALO',
    'crane': 'クレーン横丁',
    'cranekiwami': 'クレーン横丁 極',
    'capsule': 'カプセル横丁',
}
GEOCODER = 'https://msearch.gsi.go.jp/address-search/AddressSearch?q={}'

# 掲載するのは日本国内の店舗だけ（各チェーンは海外にも出店している）
PREFECTURES = (
    '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県', '茨城県', '栃木県',
    '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県', '新潟県', '富山県', '石川県', '福井県',
    '山梨県', '長野県', '岐阜県', '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府',
    '兵庫県', '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県',
    '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県',
    '鹿児島県', '沖縄県',
)

# 公式表記 → サイト上の絞り込み項目
PRIZE_WORDS = ('クレーンゲーム', 'プライズ')
PURIKURA_WORDS = ('プリントシール', 'プリクラ')
CAPSULE_WORDS = ('ガチャガチャ', 'カプセルトイ', 'ガシャポン')


def get(url: str) -> str:
    request = urllib.request.Request(url, headers={'User-Agent': UA, 'Accept-Language': 'ja'})
    with urllib.request.urlopen(request, timeout=30) as response:
        return response.read().decode('utf-8', 'replace')


def json_ld(html: str, types: tuple[str, ...]) -> dict | None:
    for block in re.findall(r'<script type="application/ld\+json"[^>]*>(.*?)</script>', html, re.S):
        try:
            data = json.loads(block)
        except json.JSONDecodeError:
            continue
        for entry in (data if isinstance(data, list) else [data]):
            if isinstance(entry, dict) and entry.get('@type') in types:
                return entry
    return None


def plain_text(html: str) -> str:
    text = re.sub(r'<script.*?</script>', '', html, flags=re.S)
    text = re.sub(r'<[^>]+>', ' ', text)
    return re.sub(r'\s+', ' ', text)


def opening_hours(ld: dict) -> list[dict]:
    return [
        {'days': spec.get('dayOfWeek'), 'opens': spec.get('opens'), 'closes': spec.get('closes')}
        for spec in ld.get('openingHoursSpecification') or []
    ]


def address_of(ld: dict) -> dict:
    address = ld.get('address') or {}
    return {
        'prefecture': address.get('addressRegion'),
        'city': address.get('addressLocality'),
        'address': ''.join(filter(None, [
            address.get('addressRegion'), address.get('addressLocality'), address.get('streetAddress'),
        ])),
        'postalCode': address.get('postalCode'),
    }


def fetch_gigo() -> list[dict]:
    """GiGOの店舗ページ。設置しているゲームの種類が明記されている。"""
    cache = CACHE / 'gigo.json'
    if cache.exists():
        return json.loads(cache.read_text(encoding='utf-8'))

    sitemap = get(GIGO_SITEMAP)
    urls = [u for u in re.findall(r'<loc>(.*?)</loc>', sitemap)
            if re.match(r'https://www\.gigo\.co\.jp/shops/[^/]+$', u)]

    shops = []
    for index, url in enumerate(urls, 1):
        try:
            html = get(url)
        except Exception as error:
            print(f'GiGO {index}/{len(urls)} 失敗 {url} {error}', flush=True)
            time.sleep(DELAY)
            continue

        ld = json_ld(html, ('LocalBusiness',))
        if ld:
            text = plain_text(html)

            def section(start: str, *ends: str) -> list[str]:
                begin = text.find(start)
                if begin < 0:
                    return []
                # 見出しが無いページもあるため、次に来そうな見出しのうち
                # 一番手前で切る。切れなかった場合は取り込まない。
                stops = [text.find(end, begin) for end in ends]
                stops = [stop for stop in stops if stop > 0]
                if not stops:
                    return []
                body = text[begin + len(start): min(stops)]
                return [word for word in body.split(' ') if word]

            shops.append({
                'chain': 'GiGO',
                'slug': 'gigo-' + url.rsplit('/', 1)[-1],
                'name': ld.get('name'),
                'tel': ld.get('telephone'),
                'hours': opening_hours(ld),
                'games': section('ゲームの種類', 'お店の特徴', '当店へのご意見', '近隣店舗'),
                'features': section('お店の特徴', '当店へのご意見', '近隣店舗'),
                'sourceUrl': url,
                'sourceLabel': 'GiGOお店情報サイト（公式）',
                **address_of(ld),
            })
        time.sleep(DELAY)

    CACHE.mkdir(exist_ok=True)
    cache.write_text(json.dumps(shops, ensure_ascii=False), encoding='utf-8')
    return shops


def fetch_namco() -> list[dict]:
    """バンダイナムコの店舗ページ。JSON-LDに緯度経度まで入っている。"""
    cache = CACHE / 'namco.json'
    if cache.exists():
        return json.loads(cache.read_text(encoding='utf-8'))

    sitemap = get(NAMCO_SITEMAP)
    slugs = sorted({
        match.group(1) for url in re.findall(r'<loc>(.*?)</loc>', sitemap)
        if (match := re.match(r'https://bandainamco-am\.co\.jp/game_center/loc/([^/]+)/', url))
    })
    slugs = [slug for slug in slugs if not slug.startswith('_')]

    shops = []
    for index, slug in enumerate(slugs, 1):
        url = NAMCO_SHOP.format(slug)
        try:
            html = get(url)
        except Exception as error:
            print(f'namco {index}/{len(slugs)} 失敗 {slug} {error}', flush=True)
            time.sleep(DELAY)
            continue

        ld = json_ld(html, ('EntertainmentBusiness', 'LocalBusiness'))
        if ld:
            geo = ld.get('geo') or {}
            shops.append({
                'chain': 'バンダイナムコアミューズメント',
                'slug': 'namco-' + slug,
                'name': ld.get('name'),
                'tel': ld.get('telephone'),
                'hours': opening_hours(ld),
                'games': [],
                'features': [],
                'lat': geo.get('latitude'),
                'lng': geo.get('longitude'),
                'sourceUrl': url,
                'sourceLabel': 'バンダイナムコ アミューズメント公式サイト',
                **address_of(ld),
            })
        time.sleep(DELAY)

    CACHE.mkdir(exist_ok=True)
    cache.write_text(json.dumps(shops, ensure_ascii=False), encoding='utf-8')
    return shops


AEON_BLOCK = re.compile(
    r'<div class="result-detail_box brand-([a-z0-9_-]+)">(.*?)<!-- / \.result-detail_box-->', re.S)


def fetch_aeon() -> list[dict]:
    """イオンファンタジーの店舗検索。一覧に名称・座標・住所・電話がまとめて載っている。"""
    cache = CACHE / 'aeon.json'
    if cache.exists():
        return json.loads(cache.read_text(encoding='utf-8'))

    shops = []
    page = 1
    while True:
        try:
            html = get(AEON_LIST.format(page))
        except Exception as error:
            print(f'イオンファンタジー {page}ページ目 失敗 {error}', flush=True)
            break

        blocks = AEON_BLOCK.findall(html)
        if not blocks:
            break

        for brand, body in blocks:
            if brand not in AEON_BRANDS:
                continue  # ゲーム機を置かないブランド

            name_match = re.search(r'class="shop-name">(.*?)</h2>', body, re.S)
            if not name_match:
                continue
            heading = name_match.group(1)
            url = re.search(r'href="([^"]+)"', heading)
            coordinates = re.search(r'<!--\s*([0-9.]+)\s*,\s*([0-9.]+)\s*-->', heading)
            name = re.sub(r'<[^>]+>', '', re.sub(r'<!--.*?-->', '', heading, flags=re.S)).strip()

            address_match = re.search(r'class="shop-add">(.*?)</p>', body, re.S)
            raw_address = re.sub(r'\s+', ' ', re.sub(r'<[^>]+>', ' ', address_match.group(1))).strip()                 if address_match else ''
            postal = re.search(r'〒\s*([0-9]{3}-[0-9]{4})', raw_address)
            address = re.sub(r'〒\s*[0-9]{3}-[0-9]{4}', '', raw_address).strip()

            floor = re.search(r'class="shop-area">(.*?)</p>', body, re.S)
            tel = re.search(r'class="tel-link">(.*?)</span>', body, re.S)
            services = re.findall(r'ico-svc-[a-z0-9_-]+"><span>(.*?)</span>', body)

            prefecture = next((p for p in PREFECTURES if address.startswith(p)), None)
            # 「三重県津市高茶屋小森町145番地」→「津市」。郡がある住所は「〇〇郡△△町」まで取る。
            city = None
            if prefecture:
                rest = address[len(prefecture):].lstrip()
                city_match = re.match(r'(?:.{1,6}?郡)?.{1,8}?[市区町村]', rest)
                if city_match:
                    city = city_match.group(0)
                    # 「四日市市」「廿日市市」のように、市名自体に「市」が入る場合がある
                    if rest[len(city):len(city) + 1] == '市':
                        city += '市'

            shops.append({
                'chain': 'イオンファンタジー（'+AEON_BRANDS[brand]+'）',
                'slug': 'aeon-' + (url.group(1).rstrip('/').rsplit('/', 1)[-1] if url
                                   else re.sub(r'[^a-z0-9]+', '-', name.lower()).strip('-')),
                'name': name,
                'prefecture': prefecture,
                'city': city,
                'address': ' '.join(filter(None, [
                    address, re.sub(r'<[^>]+>', '', floor.group(1)).strip() if floor else None,
                ])),
                'postalCode': postal.group(1) if postal else None,
                'tel': tel.group(1).strip() if tel else None,
                'lat': float(coordinates.group(1)) if coordinates else None,
                'lng': float(coordinates.group(2)) if coordinates else None,
                'hours': [],
                'games': ['カプセルトイ'] if any('カプセルトイ' in service for service in services) else [],
                'features': services,
                'sourceUrl': url.group(1) if url else AEON_LIST.format(page),
                'sourceLabel': 'イオンファンタジー公式サイト 店舗検索',
            })

        print(f'イオンファンタジー {page}ページ目 {len(shops)}件', flush=True)
        page += 1
        time.sleep(DELAY)

    CACHE.mkdir(exist_ok=True)
    cache.write_text(json.dumps(shops, ensure_ascii=False), encoding='utf-8')
    return shops


def fetch_taito() -> list[dict]:
    """タイトーの店舗一覧。住所・電話・営業時間・緯度経度がAPIから直接返る。"""
    cache = CACHE / 'taito.json'
    if cache.exists():
        return json.loads(cache.read_text(encoding='utf-8'))

    request = urllib.request.Request(TAITO_API, headers={
        'User-Agent': UA, 'Accept-Language': 'ja', 'Referer': TAITO_REFERER,
    })
    with urllib.request.urlopen(request, timeout=60) as response:
        items = json.loads(response.read().decode('utf-8', 'replace'))

    shops = []
    for item in items:
        store = item.get('StoreData') or {}
        if store.get('CountryCode') != 'JP':
            continue  # 海外店舗は載せない

        hours = []
        business_hours = (item.get('BusinessHours') or store.get('BusinessHours') or '').strip()
        if '～' in business_hours:
            opens, closes = business_hours.split('～', 1)
            hours.append({'days': None, 'opens': opens.strip(), 'closes': closes.strip()})

        zip_code = (store.get('ZipCode') or '').strip()
        holiday = (item.get('FixedHoliday') or '').strip()

        shops.append({
            'chain': 'タイトー',
            'slug': 'taito-' + store['StoreID'],
            'name': store.get('StoreName'),
            'prefecture': store.get('State'),
            'city': store.get('City'),
            'address': store.get('FullAddress') or ''.join(filter(None, [
                store.get('State'), store.get('City'), store.get('Address1'),
            ])),
            'postalCode': f'{zip_code[:3]}-{zip_code[3:]}' if len(zip_code) == 7 else None,
            'tel': (item.get('TelephoneNo') or store.get('TelephoneNo') or '').strip() or None,
            'lat': store.get('Latitude'),
            'lng': store.get('Longitude'),
            'hours': hours,
            'games': [],
            'features': [f'定休日 {holiday}'] if holiday else [],
            'sourceUrl': TAITO_STORE.format(store['StoreID']),
            'sourceLabel': 'タイトー公式サイト 店舗情報',
        })

    print(f'タイトー {len(shops)}件', flush=True)
    CACHE.mkdir(exist_ok=True)
    cache.write_text(json.dumps(shops, ensure_ascii=False), encoding='utf-8')
    return shops


def geocode(shops: list[dict]) -> None:
    """緯度経度が無い店舗を、国土地理院の住所検索APIで補う。"""
    cache_path = CACHE / 'geocode.json'
    cache = json.loads(cache_path.read_text(encoding='utf-8')) if cache_path.exists() else {}

    pending = [shop for shop in shops if not shop.get('lat') and shop.get('address')]
    for index, shop in enumerate(pending, 1):
        address = shop['address']
        if address not in cache:
            try:
                found = json.loads(get(GEOCODER.format(urllib.parse.quote(address))))
                coordinates = found[0]['geometry']['coordinates'] if found else None
                cache[address] = coordinates
            except Exception as error:
                print(f'住所検索 失敗 {address} {error}', flush=True)
                cache[address] = None
            time.sleep(GEOCODE_DELAY)
            if index % 25 == 0:
                cache_path.write_text(json.dumps(cache, ensure_ascii=False), encoding='utf-8')
                print(f'住所検索 {index}/{len(pending)}', flush=True)

        coordinates = cache.get(address)
        if coordinates:
            shop['lng'], shop['lat'] = coordinates[0], coordinates[1]

    CACHE.mkdir(exist_ok=True)
    cache_path.write_text(json.dumps(cache, ensure_ascii=False), encoding='utf-8')


def normalize_tel(tel: str | None) -> str | None:
    """「+81-570-087602」のような国際表記を、国内の表記に直す。"""
    if not tel:
        return None
    return re.sub(r'^\+81[-\s]?', '0', tel.strip())


def clean_labels(words: list[str]) -> list[str]:
    """見出しの区切りが無いページでは、後ろのキャンペーン欄まで拾ってしまう。
    見出しらしき語やページ埋め込みのコードが出てきた時点で打ち切る。"""
    stops = ('キャンペーン', 'View', '景品', 'ニュース', '近隣店舗', '当店へのご意見・ご要望はこちら')
    labels = []
    for word in words:
        if word in stops or len(word) > 20 or '{' in word or word.startswith('20'):
            break
        labels.append(word)
    return labels[:12]


def normalize_features(words: list[str]) -> list[str]:
    """「FREE Wi-Fi」「回数券 非対応」のように途中に空白が入る項目をつなぎ直す。"""
    joiners = ('Wi-Fi', '対応', '非対応', '利用可能', '利用不可', 'あり', 'なし')
    merged: list[str] = []
    for word in words:
        if merged and word in joiners:
            merged[-1] = f'{merged[-1]} {word}'
        else:
            merged.append(word)
    return merged


def has_any(words: list[str], keywords: tuple[str, ...]) -> bool:
    return any(keyword in word for word in words for keyword in keywords)


def main() -> None:
    CACHE.mkdir(exist_ok=True)
    shops = fetch_gigo() + fetch_namco() + fetch_aeon() + fetch_taito()
    geocode(shops)

    records = []
    for shop in shops:
        if not shop.get('name') or not shop.get('lat'):
            continue
        if shop.get('prefecture') not in PREFECTURES:
            continue  # 海外店舗・オンライン店舗は載せない
        games = clean_labels(shop.get('games') or [])
        records.append({
            'slug': shop['slug'],
            'name': shop['name'],
            'chain': shop['chain'],
            'prefecture': shop.get('prefecture'),
            'city': shop.get('city'),
            'address': shop.get('address'),
            'postalCode': shop.get('postalCode'),
            'tel': normalize_tel(shop.get('tel')),
            'lat': round(float(shop['lat']), 7),
            'lng': round(float(shop['lng']), 7),
            'hours': shop.get('hours') or [],
            'games': games,
            'features': normalize_features(clean_labels(shop.get('features') or [])),
            # 公式に「設置している」と書かれている場合だけ true にする
            'hasPrize': has_any(games, PRIZE_WORDS),
            'hasPurikura': has_any(games, PURIKURA_WORDS),
            'hasCapsule': has_any(games, CAPSULE_WORDS),
            'sourceUrl': shop['sourceUrl'],
            'sourceLabel': shop['sourceLabel'],
        })

    # 同じ店舗が一覧に二度出てくることがある（イオンファンタジーで4件）。
    unique = {}
    for record in records:
        unique.setdefault(record['slug'], record)
    records = list(unique.values())

    records.sort(key=lambda record: (record['prefecture'] or '', record['name']))
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    OUTPUT.write_text(json.dumps({
        'confirmedOn': date.today().isoformat(),
        'shops': records,
    }, ensure_ascii=False), encoding='utf-8')

    print(f'{len(records)}店舗を書き出しました（座標なしで除外: {len(shops) - len(records)}）')


main()
