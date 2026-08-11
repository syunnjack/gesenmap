<?php

namespace Database\Seeders;

use App\Models\GameCenter;
use Illuminate\Database\Seeder;

class GameCenterSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            // ══ 東京都 ══
            ['name'=>'ラウンドワン 渋谷店','description'=>'渋谷駅徒歩5分。UFOキャッチャー・プリクラ・カラオケ・スポッチャ完備。深夜も営業中。','lat'=>35.6602,'lng'=>139.7003,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 秋葉原店','description'=>'AKIBAランドとも近い秋葉原エリア。プライズ機多数・最新プリクラも充実。','lat'=>35.6991,'lng'=>139.7739,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 池袋P\'PARCO店','description'=>'池袋P\'PARCO内。最新音楽ゲーム・プライズ・メダルゲーム揃い。','lat'=>35.7299,'lng'=>139.7114,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 新宿3丁目店','description'=>'新宿3丁目すぐ。カードゲーム・音ゲー・プライズ機が充実。','lat'=>35.6919,'lng'=>139.7018,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco 池袋店','description'=>'バンダイナムコのアミューズメント施設。大型プライズ機あり。','lat'=>35.7297,'lng'=>139.7109,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 秋葉原店','description'=>'秋葉原の大型namco。鉄拳・太鼓の達人など定番も充実。','lat'=>35.6989,'lng'=>139.7724,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'セガ 秋葉原2号館','description'=>'セガのフラグシップ。UFOキャッチャー・音ゲー・メダル全フロア展開。','lat'=>35.6983,'lng'=>139.7719,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'東京ジョイポリス','description'=>'お台場にある大型屋内テーマパーク型ゲーセン。VRアトラクションあり。','lat'=>35.6232,'lng'=>139.7754,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'アドアーズ 上野店','description'=>'上野駅近く。プリクラが豊富な老舗ゲームセンター。','lat'=>35.7113,'lng'=>139.7746,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 渋谷3号館','description'=>'旧セガが運営するGiGO。プライズ機・カードゲーム充実。','lat'=>35.6593,'lng'=>139.7007,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 秋葉原1号館','description'=>'秋葉原の旗艦店。全フロア幅広いジャンルのゲーム機設置。','lat'=>35.6988,'lng'=>139.7715,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'パレード 吉祥寺店','description'=>'吉祥寺駅徒歩2分。プライズ機が豊富で週替わりで景品入替え。','lat'=>35.7027,'lng'=>139.5799,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 神奈川県 ══
            ['name'=>'ラウンドワン 横浜西口店','description'=>'横浜駅西口から徒歩4分。スポッチャ・ボウリングも併設。プライズ・プリクラ全完備。','lat'=>35.4671,'lng'=>139.6218,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 横浜ビブレ店','description'=>'横浜ビブレ内。音楽ゲーム・プライズが充実。','lat'=>35.4656,'lng'=>139.6213,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco 川崎ラゾーナ店','description'=>'ラゾーナ川崎PLAZAの大型namco。プライズ数百台設置。','lat'=>35.5308,'lng'=>139.6994,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 橋本店','description'=>'相模原市橋本。大型複合アミューズメント施設。','lat'=>35.5888,'lng'=>139.3283,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'アドアーズ 横浜駅前店','description'=>'横浜駅すぐのゲームセンター。プリクラとプライズが豊富。','lat'=>35.4674,'lng'=>139.6222,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            // ══ 埼玉県 ══
            ['name'=>'ラウンドワン 大宮店','description'=>'大宮駅近くの大型施設。ボウリング・スポッチャ・ゲームセンター全部揃う。','lat'=>35.9076,'lng'=>139.6248,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 大宮アルシェ店','description'=>'大宮アルシェ内。プライズ機・音ゲー・プリクラ完備。','lat'=>35.9063,'lng'=>139.6234,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 川越店','description'=>'川越の旧セガゲームセンター。地元客に人気の定番スポット。','lat'=>35.9254,'lng'=>139.4860,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            // ══ 千葉県 ══
            ['name'=>'ラウンドワン 千葉店','description'=>'千葉市内の大型ラウンドワン。カラオケ・ボウリングと合わせて利用可。','lat'=>35.6070,'lng'=>140.1234,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 幕張新都心店','description'=>'イオンモール幕張新都心内。大型namcoで施設内移動も楽。','lat'=>35.6425,'lng'=>140.0353,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 大阪府 ══
            ['name'=>'ラウンドワン 梅田店','description'=>'梅田の大型アミューズメント。プライズ・プリクラ・スポッチャが人気。','lat'=>34.7013,'lng'=>135.4975,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 難波店','description'=>'難波の中心部。深夜3時まで営業のゲームセンターとして人気。','lat'=>34.6658,'lng'=>135.5003,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 難波店','description'=>'難波駅徒歩3分。UFOキャッチャー数百台・音ゲー最新機種あり。','lat'=>34.6662,'lng'=>135.5014,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco なんばパークス店','description'=>'なんばパークス内。プライズ・プリクラ・カードゲーム。','lat'=>34.6645,'lng'=>135.5006,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 日本橋店','description'=>'大阪日本橋のオタクエリアにある旧セガゲームセンター。','lat'=>34.6694,'lng'=>135.5076,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'アミュージアム 梅田店','description'=>'梅田HEPナビオ内。プリクラ専門フロアあり。','lat'=>34.7027,'lng'=>135.5002,'has_prize'=>false,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 天王寺店','description'=>'天王寺・阿倍野エリアの大型施設。スポッチャ込みで1日楽しめる。','lat'=>34.6480,'lng'=>135.5130,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 京都府 ══
            ['name'=>'タイトーステーション 京都四条河原町店','description'=>'四条河原町の繁華街にある人気ゲーセン。プライズ・プリクラ揃い。','lat'=>35.0037,'lng'=>135.7696,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 京都ヨドバシ店','description'=>'ヨドバシ京都内。プライズ多数・家族連れにも人気。','lat'=>34.9866,'lng'=>135.7589,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 兵庫県 ══
            ['name'=>'ラウンドワン 三宮店','description'=>'神戸三宮の大型ラウンドワン。プライズ・スポッチャ・ボウリング。','lat'=>34.6918,'lng'=>135.1961,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 神戸元町店','description'=>'神戸元町商店街内。プライズ機充実・音ゲーマニアにも人気。','lat'=>34.6896,'lng'=>135.1894,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            // ══ 愛知県 ══
            ['name'=>'ラウンドワン 名古屋駅西店','description'=>'名古屋駅西口から徒歩5分。大型複合施設でゲーセン規模も最大級。','lat'=>35.1697,'lng'=>136.8818,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 栄店','description'=>'栄の繁華街中心。プライズ機・カードゲーム・プリクラ全部揃い。','lat'=>35.1695,'lng'=>136.9082,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 名古屋LACHIC店','description'=>'ラシック地下。プライズとプリクラが充実した都市型ゲームセンター。','lat'=>35.1674,'lng'=>136.9066,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'GiGO 名古屋大須店','description'=>'大須のオタクエリアにある旧セガゲームセンター。プライズが豊富。','lat'=>35.1590,'lng'=>136.9040,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            // ══ 福岡県 ══
            ['name'=>'ラウンドワン 福岡天神店','description'=>'天神の中心部。プライズ・プリクラ・スポッチャ・ボウリング完備。','lat'=>33.5903,'lng'=>130.3994,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 博多店','description'=>'博多駅徒歩5分。出張帰りにも立ち寄れる都市型ゲームセンター。','lat'=>33.5902,'lng'=>130.4200,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco キャナルシティ博多店','description'=>'キャナルシティ内。ショッピングのついでに立ち寄れる。','lat'=>33.5892,'lng'=>130.4131,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'スガイディノス 博多バスターミナル店','description'=>'博多バスターミナル内。プライズ・プリクラを中心とした大型施設。','lat'=>33.5895,'lng'=>130.4207,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            // ══ 宮城県（仙台）══
            ['name'=>'ラウンドワン 仙台泉店','description'=>'仙台市泉区の郊外大型施設。スポッチャ・ボウリングも人気。','lat'=>38.3244,'lng'=>140.8809,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 仙台PARCO2店','description'=>'仙台PARCO2内。最新音ゲー・プライズ機が揃った都市型施設。','lat'=>38.2603,'lng'=>140.8798,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 北海道（札幌）══
            ['name'=>'ラウンドワン 札幌店','description'=>'札幌市内の大型ラウンドワン。厳冬期も室内で楽しめる人気スポット。','lat'=>43.0676,'lng'=>141.3520,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 札幌ピヴォ店','description'=>'札幌駅直結のピヴォ内。プライズ・プリクラ・音ゲー充実。','lat'=>43.0641,'lng'=>141.3570,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'GiGO 札幌大通店','description'=>'大通公園近くの旧セガゲームセンター。プライズ機が豊富。','lat'=>43.0586,'lng'=>141.3533,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            // ══ 広島県 ══
            ['name'=>'ラウンドワン 広島府中店','description'=>'広島市府中町の大型施設。プライズ・スポッチャ・ボウリング。','lat'=>34.3858,'lng'=>132.4947,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 広島本通り店','description'=>'広島本通り商店街内。プリクラとプライズが人気。','lat'=>34.3958,'lng'=>132.4596,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            // ══ 静岡県 ══
            ['name'=>'ラウンドワン 静岡店','description'=>'静岡駅近くの大型ラウンドワン。プライズ・プリクラ・スポッチャ全完備。','lat'=>34.9716,'lng'=>138.3858,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            // ══ 千葉/首都圏追加 ══
            ['name'=>'オアシスパーク ゲームセンター 八景島','description'=>'横浜・八景島シーパラダイス内。アミューズメント施設としても人気。','lat'=>35.3217,'lng'=>139.6394,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 新百合ヶ丘OPA店','description'=>'新百合ヶ丘OPA内の大型namco。川崎市郊外の家族連れに人気。','lat'=>35.6046,'lng'=>139.4895,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ゲームシティ 立川店','description'=>'立川駅近く。プライズ機とカードゲームが充実した老舗ゲームセンター。','lat'=>35.6980,'lng'=>139.4139,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            // ══ プリクラ専門・カプセル特化 ══
            ['name'=>'ミラクル 秋葉原店','description'=>'秋葉原のプリクラ専門フロア。最新機種が常時20台以上設置。','lat'=>35.6989,'lng'=>139.7725,'has_prize'=>false,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'カプセルステーション 渋谷店','description'=>'カプセルトイ専門のアミューズメント施設。300台以上のガチャガチャが揃う。','lat'=>35.6598,'lng'=>139.7014,'has_prize'=>false,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ガシャポンバンダイオフィシャルショップ 秋葉原','description'=>'バンダイ公式のガチャガチャ専門店。限定品も多数取り扱い。','lat'=>35.6993,'lng'=>139.7727,'has_prize'=>false,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'プリクラの森 新宿店','description'=>'新宿エリアのプリクラ専門店。女子に人気の最新機種ばかり。','lat'=>35.6897,'lng'=>139.7014,'has_prize'=>false,'has_purikura'=>true,'has_capsule'=>false],
            // ══ その他 ══
            ['name'=>'ゲームポイント 高田馬場店','description'=>'高田馬場の老舗ゲームセンター。格闘ゲーマーが多く集まる聖地。','lat'=>35.7130,'lng'=>139.7033,'has_prize'=>false,'has_purikura'=>false,'has_capsule'=>false],
            ['name'=>'Hey 秋葉原','description'=>'秋葉原の格闘ゲーム・シューティングゲームの聖地として有名。','lat'=>35.6988,'lng'=>139.7720,'has_prize'=>false,'has_purikura'=>false,'has_capsule'=>false],
        ];

        foreach ($locations as $data) {
            GameCenter::updateOrCreate(
                ['name' => $data['name'], 'lat' => $data['lat']],
                $data
            );
        }
    }
}
