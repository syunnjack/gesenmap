<?php

namespace Database\Seeders;

use App\Models\GameCenter;
use Illuminate\Database\Seeder;

class GameCenterSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name'=>'ラウンドワン 有楽町店','description'=>'銀座・有楽町エリア。プライズ・プリクラ・ボウリング。都心型の大型施設。','lat'=>35.6724,'lng'=>139.7625,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 蒲田店','description'=>'蒲田駅近く。南東京の人気スポット。深夜営業あり。','lat'=>35.5627,'lng'=>139.7163,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 渋谷店','description'=>'渋谷センター街内。プライズ・音ゲー・メダルが充実。','lat'=>35.6599,'lng'=>139.6997,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'タイトーステーション 立川北口店','description'=>'立川駅北口。プライズ機多数・音ゲーマニアにも人気。','lat'=>35.6985,'lng'=>139.4141,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 多摩センター店','description'=>'多摩センターパルテノン通り内。プライズ・プリクラ。','lat'=>35.6354,'lng'=>139.4392,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'GiGO 池袋P'PARCO店','description'=>'池袋P'PARCOの旧セガ系GiGO。音ゲー・プライズ充実。','lat'=>35.7301,'lng'=>139.711,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'GiGO 高田馬場店','description'=>'高田馬場駅近く。格闘ゲーム愛好者に人気。','lat'=>35.7132,'lng'=>139.7036,'has_prize'=>false,'has_purikura'=>false,'has_capsule'=>false],
            ['name'=>'アドアーズ 秋葉原店','description'=>'秋葉原の大型アドアーズ。プライズ・プリクラが豊富。','lat'=>35.6994,'lng'=>139.7718,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'プレイランド 新宿歌舞伎町店','description'=>'歌舞伎町エリア。深夜も賑わうゲームセンター。','lat'=>35.6938,'lng'=>139.7044,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ゲームパニック 八王子店','description'=>'八王子駅近くの複合アミューズメント。プライズ・プリクラ。','lat'=>35.6565,'lng'=>139.3388,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 藤沢店','description'=>'藤沢駅近くの大型ラウンドワン。スポッチャ込み1日楽しめる。','lat'=>35.3387,'lng'=>139.4901,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 相模大野店','description'=>'相模大野駅直結。プライズ・プリクラ・ボウリング全完備。','lat'=>35.5407,'lng'=>139.4341,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 横浜駅ジョイナス店','description'=>'横浜ジョイナス内。アクセス抜群の都市型ゲームセンター。','lat'=>35.4659,'lng'=>139.6208,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'namco 港北TOKYU店','description'=>'港北東急SC内。プライズ多数・家族向け施設。','lat'=>35.5413,'lng'=>139.5877,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 横浜関内店','description'=>'横浜関内・馬車道エリア。プライズとカードゲーム充実。','lat'=>35.4488,'lng'=>139.6417,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ゲームパニック 厚木店','description'=>'厚木市の郊外型大型ゲームセンター。プライズ豊富。','lat'=>35.4435,'lng'=>139.3594,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 越谷店','description'=>'越谷レイクタウン近く。プライズ・スポッチャ・ボウリング。','lat'=>35.8957,'lng'=>139.7892,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 与野本町店','description'=>'与野本町駅近く。プライズ・音ゲー充実の埼玉店。','lat'=>35.9042,'lng'=>139.6175,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco 狭山店','description'=>'狭山市のnamco。ファミリー層に人気のゲームセンター。','lat'=>35.8531,'lng'=>139.4095,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 浦和店','description'=>'浦和駅近くの旧セガ系GiGO。格闘ゲーム・プライズ。','lat'=>35.8563,'lng'=>139.6561,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>false],
            ['name'=>'ラウンドワン 柏店','description'=>'柏駅近くの大型ラウンドワン。プライズ・プリクラ全完備。','lat'=>35.8685,'lng'=>139.9744,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 船橋LaLaport店','description'=>'ラパルト船橋内。プライズ機充実・音ゲーも人気。','lat'=>35.6977,'lng'=>139.9978,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 津田沼店','description'=>'津田沼駅近くの旧セガ系GiGO。プライズ・音ゲー。','lat'=>35.6841,'lng'=>140.0221,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 苫小牧店','description'=>'苫小牧市の大型ラウンドワン。道南エリアの人気スポット。','lat'=>42.6358,'lng'=>141.6044,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 旭川店','description'=>'旭川市内のタイトーステーション。プライズ・プリクラ完備。','lat'=>43.7702,'lng'=>142.3648,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'スガイディノス 札幌北24条店','description'=>'札幌北部の老舗ゲームセンター。プライズが充実。','lat'=>43.0879,'lng'=>141.3564,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'GiGO 函館店','description'=>'函館市内の旧セガゲームセンター。プライズ・音ゲー。','lat'=>41.7689,'lng'=>140.7291,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 仙台長町店','description'=>'仙台市長町の大型施設。プライズ・スポッチャ。','lat'=>38.2115,'lng'=>140.8726,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 仙台一番町店','description'=>'仙台一番町の繁華街。プリクラ・プライズ充実。','lat'=>38.2605,'lng'=>140.8706,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 仙台クリスロード店','description'=>'仙台クリスロード商店街内。プライズ・格闘ゲーム。','lat'=>38.26,'lng'=>140.8721,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>false],
            ['name'=>'ゲームパニック 盛岡店','description'=>'岩手県盛岡市の大型ゲームセンター。プライズ・プリクラ。','lat'=>39.7031,'lng'=>141.1527,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ゲームパニック 秋田店','description'=>'秋田市の大型ゲームセンター。プライズ機が充実。','lat'=>39.7182,'lng'=>140.1023,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 新潟店','description'=>'新潟駅近くの大型ラウンドワン。プライズ・スポッチャ。','lat'=>37.9162,'lng'=>139.0604,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 新潟NEXT21店','description'=>'新潟NEXT21内のタイトーステーション。','lat'=>37.9218,'lng'=>139.0593,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 長野店','description'=>'長野市の大型ラウンドワン。プライズ・スポッチャ全完備。','lat'=>36.656,'lng'=>138.1871,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 金沢店','description'=>'金沢市内の大型ラウンドワン。プライズ・プリクラ。','lat'=>36.5947,'lng'=>136.6259,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 富山店','description'=>'富山市の旧セガゲームセンター。プライズ・音ゲー。','lat'=>36.6958,'lng'=>137.2137,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 浜松店','description'=>'浜松市内の大型ラウンドワン。スポッチャ・ボウリング。','lat'=>34.7108,'lng'=>137.7269,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 浜松駅前店','description'=>'浜松駅前の繁華街。プライズ・プリクラ・音ゲー。','lat'=>34.7038,'lng'=>137.7342,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 豊橋店','description'=>'豊橋市の大型ラウンドワン。プライズ・スポッチャ完備。','lat'=>34.7697,'lng'=>137.3918,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション イオンモール名古屋みなと店','description'=>'イオンモール名古屋みなと内。プライズ・プリクラ充実。','lat'=>35.1003,'lng'=>136.8874,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 岡崎店','description'=>'岡崎市のnamco。プライズ・カードゲーム充実。','lat'=>34.952,'lng'=>137.1762,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 四日市店','description'=>'四日市市の大型ラウンドワン。スポッチャ・ボウリング。','lat'=>34.9724,'lng'=>136.6247,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 草津店','description'=>'滋賀県草津市の大型施設。プライズ・プリクラ・スポッチャ。','lat'=>35.012,'lng'=>135.9626,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション イオンモール奈良登美ヶ丘店','description'=>'奈良の大型イオン内。プライズ・音ゲー充実。','lat'=>34.7317,'lng'=>135.7892,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 吹田店','description'=>'吹田市の大型ラウンドワン。プライズ・スポッチャ・ボウリング。','lat'=>34.7581,'lng'=>135.5168,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 八尾店','description'=>'東大阪・八尾エリアの大型施設。プライズ充実。','lat'=>34.6264,'lng'=>135.6007,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 心斎橋店','description'=>'心斎橋筋の繁華街。プリクラ・プライズ・音ゲー。','lat'=>34.6725,'lng'=>135.5011,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'namco 枚方T-SITE店','description'=>'枚方T-SITE内の大型namco。プライズ・プリクラ完備。','lat'=>34.8133,'lng'=>135.6456,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 心斎橋店','description'=>'心斎橋の旧セガ系GiGO。プライズ・格闘ゲーム充実。','lat'=>34.6729,'lng'=>135.5015,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 尼崎店','description'=>'尼崎市の大型ラウンドワン。阪神沿線エリアで人気。','lat'=>34.7286,'lng'=>135.4081,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 姫路店','description'=>'姫路市の繁華街。プライズ・プリクラ・音ゲー。','lat'=>34.8394,'lng'=>134.6932,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'namco 明石店','description'=>'明石市のnamco。プライズ充実・家族連れにも人気。','lat'=>34.6427,'lng'=>134.9966,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 岡山店','description'=>'岡山市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>34.6551,'lng'=>133.9195,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 岡山一番街店','description'=>'岡山一番街内のタイトーステーション。','lat'=>34.657,'lng'=>133.9193,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'タイトーステーション 広島パルコ店','description'=>'広島パルコ内。プライズ・プリクラ充実の都市型施設。','lat'=>34.3966,'lng'=>132.4598,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'namco 広島ゆめタウン店','description'=>'ゆめタウン広島内。プライズ多数・家族連れに人気。','lat'=>34.3812,'lng'=>132.4531,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 山口宇部店','description'=>'山口県宇部市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>33.9516,'lng'=>131.2476,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 高松店','description'=>'高松市の大型ラウンドワン。プライズ・プリクラ。','lat'=>34.3429,'lng'=>134.0459,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 高松店','description'=>'高松市中心部のタイトーステーション。','lat'=>34.3443,'lng'=>134.0465,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 松山店','description'=>'松山市の大型ラウンドワン。プライズ・スポッチャ全完備。','lat'=>33.8398,'lng'=>132.7655,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 徳島店','description'=>'徳島市の大型施設。プライズ・プリクラ・ボウリング。','lat'=>34.0727,'lng'=>134.556,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 高知店','description'=>'高知市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>33.5593,'lng'=>133.5311,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 小倉店','description'=>'北九州市小倉の大型施設。プライズ・スポッチャ。','lat'=>33.8851,'lng'=>130.875,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 小倉店','description'=>'小倉駅近くのタイトーステーション。プライズ・音ゲー。','lat'=>33.8827,'lng'=>130.874,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'GiGO 福岡パルコ店','description'=>'福岡パルコの旧セガ系GiGO。プライズ・格闘ゲーム。','lat'=>33.59,'lng'=>130.3995,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 佐賀店','description'=>'佐賀市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>33.2637,'lng'=>130.3006,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 長崎店','description'=>'長崎市の大型施設。プライズ・プリクラ完備。','lat'=>32.7502,'lng'=>129.8782,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 熊本店','description'=>'熊本市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>32.8031,'lng'=>130.7079,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 熊本サクラ町店','description'=>'熊本市中心部のタイトーステーション。プライズ・音ゲー。','lat'=>32.8018,'lng'=>130.7012,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 大分店','description'=>'大分市の大型ラウンドワン。スポッチャ・ボウリング。','lat'=>33.2382,'lng'=>131.6068,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 宮崎店','description'=>'宮崎市の大型施設。プライズ・プリクラ・スポッチャ。','lat'=>31.911,'lng'=>131.4233,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 鹿児島店','description'=>'鹿児島市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>31.5966,'lng'=>130.5571,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 鹿児島アミュプラザ店','description'=>'鹿児島アミュプラザ内。プライズ・プリクラ充実。','lat'=>31.5837,'lng'=>130.5547,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'ラウンドワン 沖縄那覇店','description'=>'那覇市内の大型ラウンドワン。観光客にも人気。プライズ・プリクラ。','lat'=>26.2124,'lng'=>127.6792,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション 那覇メインプレイス店','description'=>'那覇メインプレイス内。プライズ・音ゲー充実。','lat'=>26.2131,'lng'=>127.6798,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'namco 沖縄ライカム店','description'=>'イオンモールライカム内の大型namco。プライズ・プリクラ。','lat'=>26.3629,'lng'=>127.7827,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'ラウンドワン 京都宇治店','description'=>'宇治市の大型ラウンドワン。プライズ・スポッチャ。','lat'=>34.8848,'lng'=>135.798,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 京都BAL店','description'=>'京都BAL内の旧セガ系GiGO。プライズ・音ゲー。','lat'=>35.006,'lng'=>135.7673,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ラウンドワン 武蔵村山店','description'=>'武蔵村山市。郊外型大型施設。プライズ・プリクラ・スポッチャ。','lat'=>35.7549,'lng'=>139.3885,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'タイトーステーション ルミネ横浜店','description'=>'ルミネ横浜内のタイトー。プライズ・音ゲー。','lat'=>35.4655,'lng'=>139.6212,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
            ['name'=>'namco イオンモール浦和美園店','description'=>'浦和美園のイオン内大型namco。プライズ充実。','lat'=>35.8858,'lng'=>139.7126,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>true],
            ['name'=>'GiGO 町田店','description'=>'町田駅近くの旧セガ系GiGO。プライズ・格闘ゲーム。','lat'=>35.5445,'lng'=>139.4457,'has_prize'=>true,'has_purikura'=>false,'has_capsule'=>true],
            ['name'=>'ゲームパニック 下北沢店','description'=>'下北沢の若者に人気のゲームセンター。プリクラ・プライズ。','lat'=>35.6609,'lng'=>139.6681,'has_prize'=>true,'has_purikura'=>true,'has_capsule'=>false],
        ];

        foreach ($locations as $data) {
            GameCenter::updateOrCreate(
                ['name' => $data['name'], 'lat' => $data['lat']],
                $data
            );
        }
    }
}
