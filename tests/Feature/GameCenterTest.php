<?php

namespace Tests\Feature;

use App\Models\GameCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_lists_registered_game_centers(): void
    {
        GameCenter::create([
            'name' => 'テストゲームセンター',
            'description' => 'テスト説明',
            'lat' => 35.6812,
            'lng' => 139.7671,
            'has_prize' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テストゲームセンター');
    }

    public function test_a_game_center_can_be_submitted(): void
    {
        $response = $this->post(route('game-centers.store'), [
            'name' => '新規ゲームセンター',
            'description' => '説明文',
            'lat' => 35.0,
            'lng' => 135.0,
            'has_prize' => 'on',
            'has_purikura' => 'on',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('game_centers', [
            'name' => '新規ゲームセンター',
            'lat' => 35.0,
            'lng' => 135.0,
            'has_prize' => true,
            'has_purikura' => true,
            'has_capsule' => false,
        ]);
        $this->assertDatabaseCount('game_centers', 1);
    }

    public function test_submitting_without_required_fields_fails_validation(): void
    {
        $response = $this->post(route('game-centers.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'lat', 'lng']);
        $this->assertDatabaseCount('game_centers', 0);
    }

    public function test_filter_by_prize_excludes_non_matching_game_centers(): void
    {
        GameCenter::create(['name' => 'プライズ店', 'lat' => 35.0, 'lng' => 135.0, 'has_prize' => true]);
        GameCenter::create(['name' => '通常店', 'lat' => 35.1, 'lng' => 135.1, 'has_prize' => false]);

        $response = $this->get('/?prize=1');

        $response->assertStatus(200);
        $response->assertSee('プライズ店');
        $response->assertDontSee('通常店');
    }
}
