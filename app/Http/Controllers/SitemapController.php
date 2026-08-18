<?php

namespace App\Http\Controllers;

use App\Models\GameCenter;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.3'],
            ['loc' => route('areas.index'), 'priority' => '0.8'],
        ])->merge(
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
