<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class GameCenterOfficialSeeder extends Seeder
{
    /**
     * チェーン公式サイトで確認した店舗を取り込む。
     *
     * 元データは scripts/build-game-center-data.py が database/data/game-centers.json に
     * 書き出す。slug をキーにした upsert なので、店舗情報が変わったら作り直して流し直す。
     * 利用者が投稿した店舗（slug が無い）はここでは触らない。
     */
    private const CHUNK = 40; // SQLiteのプレースホルダ上限に収まる大きさ

    public function run(): void
    {
        $path = database_path('data/game-centers.json');

        if (! File::exists($path)) {
            throw new RuntimeException('database/data/game-centers.json が見つかりません。');
        }

        $payload = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        $shops = $payload['shops'] ?? [];
        $confirmedOn = $payload['confirmedOn'] ?? null;

        if ($shops === []) {
            throw new RuntimeException('店舗データが空です。');
        }

        $now = now();
        $written = 0;

        foreach (array_chunk($shops, self::CHUNK) as $chunk) {
            $rows = [];

            foreach ($chunk as $shop) {
                $rows[] = [
                    'slug' => $shop['slug'],
                    'name' => $shop['name'],
                    'chain' => $shop['chain'],
                    'prefecture' => $shop['prefecture'],
                    'city' => $shop['city'],
                    'address' => $shop['address'],
                    'postal_code' => $shop['postalCode'],
                    'tel' => $shop['tel'],
                    'lat' => $shop['lat'],
                    'lng' => $shop['lng'],
                    'hours' => json_encode($shop['hours'], JSON_UNESCAPED_UNICODE),
                    'games' => json_encode($shop['games'], JSON_UNESCAPED_UNICODE),
                    'features' => json_encode($shop['features'], JSON_UNESCAPED_UNICODE),
                    'has_prize' => $shop['hasPrize'],
                    'has_purikura' => $shop['hasPurikura'],
                    'has_capsule' => $shop['hasCapsule'],
                    'source_url' => $shop['sourceUrl'],
                    'source_label' => $shop['sourceLabel'],
                    'confirmed_on' => $confirmedOn,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('game_centers')->upsert(
                $rows,
                ['slug'],
                [
                    'name', 'chain', 'prefecture', 'city', 'address', 'postal_code', 'tel',
                    'lat', 'lng', 'hours', 'games', 'features',
                    'has_prize', 'has_purikura', 'has_capsule',
                    'source_url', 'source_label', 'confirmed_on', 'updated_at',
                ]
            );

            $written += count($rows);
        }

        // 公式サイトから消えた店舗（閉店・掲載終了）は、こちらからも下げる。
        // 利用者が投稿した店舗（slug が無い）は対象外。
        $removed = DB::table('game_centers')
            ->whereNotNull('slug')
            ->whereNotIn('slug', array_column($shops, 'slug'))
            ->delete();

        $this->command?->info(number_format($written).'店舗を取り込みました（'.$confirmedOn.'時点の公式情報）。'
            .($removed > 0 ? number_format($removed).'店舗を掲載から外しました。' : ''));
    }
}
