<?php

namespace Tests\Feature;

use App\Models\GameCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCenterPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_page_shows_official_details_and_source(): void
    {
        $shop = GameCenter::create($this->shopAttributes());

        $this->get('/g/'.$shop->slug)
            ->assertOk()
            ->assertSee('GiGO我孫子')
            ->assertSee('千葉県我孫子市柴崎天王谷47-1')
            ->assertSee('クレーンゲーム')
            ->assertSee('GiGOお店情報サイト（公式）');
    }

    public function test_area_pages_list_shops_of_the_prefecture(): void
    {
        GameCenter::create($this->shopAttributes());

        $this->get('/area')->assertOk()->assertSee('千葉県');
        $this->get('/area/chiba')
            ->assertOk()
            ->assertSee('GiGO我孫子')
            ->assertSee('我孫子市');
    }

    public function test_unknown_area_and_slug_return_not_found(): void
    {
        GameCenter::create($this->shopAttributes());

        $this->get('/area/atlantis')->assertNotFound();
        $this->get('/area/tokyo')->assertNotFound();  // 掲載店舗が無い都道府県
        $this->get('/g/unknown-shop')->assertNotFound();
    }

    public function test_sitemap_lists_store_and_area_pages(): void
    {
        $shop = GameCenter::create($this->shopAttributes());

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/g/'.$shop->slug, false)
            ->assertSee('/area/chiba', false);
    }

    /** 公式サイトから取り込む項目にそろえた1店舗分のデータ。 */
    private function shopAttributes(): array
    {
        return [
            'slug' => 'gigo-abiko',
            'name' => 'GiGO我孫子',
            'chain' => 'GiGO',
            'prefecture' => '千葉県',
            'city' => '我孫子市',
            'address' => '千葉県我孫子市柴崎天王谷47-1',
            'postal_code' => '270-1177',
            'tel' => '070-1458-7828',
            'lat' => 35.8756,
            'lng' => 140.0281,
            'hours' => [['days' => ['Monday'], 'opens' => '10:00', 'closes' => '00:00']],
            'games' => ['クレーンゲーム', 'プリントシール', 'ガチャガチャ'],
            'features' => ['FREE Wi-Fi', '完全分煙'],
            'has_prize' => true,
            'has_purikura' => true,
            'has_capsule' => true,
            'source_url' => 'https://www.gigo.co.jp/shops/abiko',
            'source_label' => 'GiGOお店情報サイト（公式）',
            'confirmed_on' => '2026-08-19',
        ];
    }
}
