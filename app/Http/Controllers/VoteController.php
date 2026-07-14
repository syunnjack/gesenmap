<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;
use App\Models\LocationVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request, GameCenter $gameCenter): RedirectResponse
    {
        $ipHash = hash('sha256', $request->ip());

        $alreadyVoted = LocationVote::where('game_center_id', $gameCenter->id)
            ->where('ip_hash', $ipHash)
            ->exists();

        if ($alreadyVoted) {
            return back()->with('vote_message', 'この施設にはすでに投票済みです。');
        }

        LocationVote::create([
            'game_center_id' => $gameCenter->id,
            'ip_hash' => $ipHash,
        ]);

        return back()->with('vote_message', '投票しました！');
    }
}
