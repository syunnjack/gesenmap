<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = GameCenter::query()->withCount(['votes', 'reviews']);

        if ($request->filled('prize')) {
            $query->where('has_prize', true);
        }
        if ($request->filled('purikura')) {
            $query->where('has_purikura', true);
        }
        if ($request->filled('capsule')) {
            $query->where('has_capsule', true);
        }

        // 地図には全件を出すが、下の一覧は都道府県から辿ってもらう。
        $locations = $query->orderBy('prefecture')->orderBy('name')->get();

        return view('home', [
            'locations' => $locations,
            'prefectures' => $this->prefectureCounts(),
            'total' => GameCenter::count(),
        ]);
    }

    public function show(string $slug)
    {
        $gameCenter = GameCenter::query()
            ->withCount('votes')
            ->with('reviews')
            ->where('slug', $slug)
            ->first();

        if ($gameCenter === null) {
            throw new NotFoundHttpException;
        }

        $nearby = GameCenter::query()
            ->where('id', '!=', $gameCenter->id)
            ->when($gameCenter->city, fn ($query, $city) => $query->where('city', $city))
            ->when(! $gameCenter->city, fn ($query) => $query->where('prefecture', $gameCenter->prefecture))
            ->orderBy('name')
            ->limit(12)
            ->get();

        if ($nearby->isEmpty() && $gameCenter->prefecture) {
            $nearby = GameCenter::query()
                ->where('id', '!=', $gameCenter->id)
                ->where('prefecture', $gameCenter->prefecture)
                ->orderBy('name')
                ->limit(12)
                ->get();
        }

        return view('game-centers.show', [
            'gameCenter' => $gameCenter,
            'nearby' => $nearby,
        ]);
    }

    public function areas()
    {
        return view('areas.index', [
            'prefectures' => $this->prefectureCounts(),
            'total' => GameCenter::whereNotNull('prefecture')->count(),
        ]);
    }

    public function area(string $prefectureSlug)
    {
        $prefecture = GameCenter::prefectureForSlug($prefectureSlug);

        if ($prefecture === null) {
            throw new NotFoundHttpException;
        }

        $shops = GameCenter::query()
            ->where('prefecture', $prefecture)
            ->withCount(['votes', 'reviews'])
            ->orderBy('city')
            ->orderBy('name')
            ->get();

        if ($shops->isEmpty()) {
            throw new NotFoundHttpException;
        }

        return view('areas.show', [
            'prefecture' => $prefecture,
            'prefectureSlug' => $prefectureSlug,
            'shops' => $shops,
            'byCity' => $shops->groupBy(fn (GameCenter $shop) => $shop->city ?: 'その他'),
            'prefectures' => $this->prefectureCounts(),
        ]);
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

    /** 都道府県ごとの掲載件数（多い順）。 */
    private function prefectureCounts(): Collection
    {
        return GameCenter::query()
            ->selectRaw('prefecture, COUNT(*) as total')
            ->whereNotNull('prefecture')
            ->groupBy('prefecture')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'prefecture' => $row->prefecture,
                'slug' => GameCenter::slugForPrefecture($row->prefecture),
                'total' => (int) $row->total,
            ])
            ->filter(fn (array $row) => $row['slug'] !== null)
            ->values();
    }
}
