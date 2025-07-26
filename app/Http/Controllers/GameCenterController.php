<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Cache;
use App\Models\LocationVote;
use App\Models\FigureItem;
class GameCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = GameCenter::all();
        $query = GameCenter::query();

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
        return view('map', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        GameCenter::create($request->all());
        $data = $request->only([
        'name',
        'description',
        'lat',
        'lng'
    ]);

    // チェックボックスの "on" を true/false に変換
    $data['has_prize'] = $request->has('has_prize');
    $data['has_purikura'] = $request->has('has_purikura');
    $data['has_capsule'] = $request->has('has_capsule');

    GameCenter::create($data);
    return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(GameCenter $gameCenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameCenter $gameCenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameCenter $gameCenter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameCenter $gameCenter)
    {
        //
    }
}
