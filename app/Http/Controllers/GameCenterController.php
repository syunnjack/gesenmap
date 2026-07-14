<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;
use Illuminate\Http\Request;

class GameCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = GameCenter::query()->withCount(['votes', 'reviews'])->with('reviews');

        if ($request->filled('prize')) {
            $query->where('has_prize', true);
        }
        if ($request->filled('purikura')) {
            $query->where('has_purikura', true);
        }
        if ($request->filled('capsule')) {
            $query->where('has_capsule', true);
        }

        $locations = $query->get();

        return view('home', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        GameCenter::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'has_prize' => $request->boolean('has_prize'),
            'has_purikura' => $request->boolean('has_purikura'),
            'has_capsule' => $request->boolean('has_capsule'),
        ]);

        return redirect('/')->with('submit_success', true);
    }
}
