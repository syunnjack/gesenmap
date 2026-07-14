<?php

namespace Tests\Feature;

use App\Models\GameCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_review_can_be_submitted(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $response = $this->post(route('reviews.store', $gameCenter), [
            'nickname' => 'テスト太郎',
            'rating' => 5,
            'comment' => 'プライズが充実していて楽しかったです。',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'game_center_id' => $gameCenter->id,
            'nickname' => 'テスト太郎',
            'rating' => 5,
        ]);
    }

    public function test_review_without_nickname_defaults_to_anonymous(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $this->post(route('reviews.store', $gameCenter), [
            'rating' => 4,
            'comment' => '良かったです。',
        ]);

        $this->assertDatabaseHas('reviews', ['nickname' => '匿名']);
    }

    public function test_honeypot_field_silently_rejects_the_review(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $this->post(route('reviews.store', $gameCenter), [
            'rating' => 5,
            'comment' => 'スパムコメントです。',
            'website' => 'https://spam.example.com',
        ]);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_ng_word_is_rejected(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $response = $this->post(route('reviews.store', $gameCenter), [
            'rating' => 1,
            'comment' => 'この店員は死ねばいいのに',
        ]);

        $response->assertSessionHasErrors('comment');
        $this->assertDatabaseCount('reviews', 0);
    }
}
