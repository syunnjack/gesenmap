<?php

namespace Tests\Feature;

use App\Models\GameCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_vote_can_be_cast(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $response = $this->post(route('game-centers.vote', $gameCenter));

        $response->assertRedirect();
        $this->assertDatabaseCount('location_votes', 1);
    }

    public function test_duplicate_vote_from_same_ip_is_rejected(): void
    {
        $gameCenter = GameCenter::create(['name' => 'テスト店', 'lat' => 35.0, 'lng' => 135.0]);

        $this->post(route('game-centers.vote', $gameCenter));
        $this->post(route('game-centers.vote', $gameCenter));

        $this->assertDatabaseCount('location_votes', 1);
    }
}
