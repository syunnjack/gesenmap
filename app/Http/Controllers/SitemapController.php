<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('rules'), 'priority' => '0.6'],
            ['loc' => route('about'), 'priority' => '0.3'],
            ['loc' => route('areas.index'), 'priority' => '0.8'],
            ['loc' => route('categories.index'), 'priority' => '0.8'],
        ])->merge(
            // 遊びの種類のページ
            collect(array_keys(GameCenter::CATEGORIES))->map(fn (string $slug) => [
                'loc' => route('categories.show', $slug),
                'priority' => '0.7',
            ])
        )->merge(
            // 都道府県 × 遊びの種類。店舗が1件も無い組み合わせは404にしているので、
            // 実際に店舗がある組み合わせだけを出す
            GameCenter::query()
                ->official()
                ->whereNotNull('prefecture')
                ->get(['prefecture', 'categories'])
                ->flatMap(fn (GameCenter $shop) => collect($shop->categories ?? [])
                    ->map(fn (string $category) => [$shop->prefecture, $category]))
                ->unique(fn (array $pair) => implode('/', $pair))
                ->map(function (array $pair) {
                    [$prefecture, $category] = $pair;
                    $slug = GameCenter::slugForPrefecture((string) $prefecture);

                    return $slug === null ? null : [
                        'loc' => route('areas.category', [$slug, $category]),
                        'priority' => '0.6',
                    ];
                })
                ->filter()
        )->merge(
            // 掲載店舗がある都道府県のページ
            GameCenter::query()
                ->selectRaw('prefecture, MAX(updated_at) as updated')
                ->whereNotNull('prefecture')
                ->groupBy('prefecture')
                ->get()
                ->map(fn ($row) => GameCenter::slugForPrefecture((string) $row->prefecture))
                ->filter()
                ->map(fn (string $slug) => [
                    'loc' => route('areas.show', $slug),
                    'priority' => '0.7',
                ])
        )->merge(
            GameCenter::query()
                ->whereNotNull('slug')
                ->select(['slug', 'updated_at'])
                ->get()
                ->map(fn (GameCenter $shop) => [
                    'loc' => route('game-centers.show', $shop->slug),
                    'priority' => '0.6',
                    'lastmod' => $shop->updated_at?->toAtomString(),
                ])
        );

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
