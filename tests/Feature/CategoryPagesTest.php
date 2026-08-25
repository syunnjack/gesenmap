<?php

namespace Tests\Feature;

use App\Models\GameCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_index_counts_shops_per_category(): void
    {
        GameCenter::create($this->shopAttributes());

        $this->get('/category')
            ->assertOk()
            ->assertSee('プライズ（クレーンゲーム）')
            ->assertSee('カプセルトイ');
    }

    public function test_category_page_lists_prefectures_that_have_shops(): void
    {
        GameCenter::create($this->shopAttributes());

        $this->get('/category/prize')->assertOk()->assertSee('千葉県');
        $this->get('/area/chiba/prize')->assertOk()->assertSee('GiGO我孫子');
    }

    public function test_a_category_the_shop_does_not_have_is_not_found(): void
    {
        GameCenter::create($this->shopAttributes());

        // この店には該当が無いので、空のページを作らず404にする
        $this->get('/area/chiba/arcade')->assertNotFound();
        $this->get('/category/nosuch')->assertNotFound();
    }

    public function test_shop_without_categories_is_shown_as_unconfirmed(): void
    {
        GameCenter::create([...$this->shopAttributes(), 'slug' => 'namco-x', 'name' => 'namcoテスト', 'categories' => []]);

        $this->get('/g/namco-x')
            ->assertOk()
            ->assertSee('未確認');
    }

    private function shopAttributes(): array
    {
        return [
            'slug' => 'gigo-abiko',
            'name' => 'GiGO我孫子',
            'chain' => 'GiGO',
            'prefecture' => '千葉県',
            'city' => '我孫子市',
            'address' => '千葉県我孫子市柴崎天王谷47-1',
            'lat' => 35.8756,
            'lng' => 140.0281,
            'games' => ['クレーンゲーム', 'ガチャガチャ'],
            'categories' => ['prize', 'capsule'],
            'category_source' => 'machines',
            'has_prize' => true,
            'has_capsule' => true,
            'source_url' => 'https://www.gigo.co.jp/shops/abiko',
            'source_label' => 'GiGOお店情報サイト（公式）',
            'confirmed_on' => '2026-08-19',
        ];
    }
}
