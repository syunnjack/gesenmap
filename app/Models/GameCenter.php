<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCenter extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'chain',
        'prefecture',
        'city',
        'address',
        'postal_code',
        'tel',
        'hours',
        'games',
        'features',
        'source_url',
        'source_label',
        'confirmed_on',
        'description',
        'lat',
        'lng',
        'has_prize',
        'has_purikura',
        'has_capsule',
    ];

    /** 都道府県ページのURLに使うローマ字。 */
    public const PREFECTURE_SLUGS = [
        '北海道' => 'hokkaido', '青森県' => 'aomori', '岩手県' => 'iwate', '宮城県' => 'miyagi',
        '秋田県' => 'akita', '山形県' => 'yamagata', '福島県' => 'fukushima', '茨城県' => 'ibaraki',
        '栃木県' => 'tochigi', '群馬県' => 'gunma', '埼玉県' => 'saitama', '千葉県' => 'chiba',
        '東京都' => 'tokyo', '神奈川県' => 'kanagawa', '新潟県' => 'niigata', '富山県' => 'toyama',
        '石川県' => 'ishikawa', '福井県' => 'fukui', '山梨県' => 'yamanashi', '長野県' => 'nagano',
        '岐阜県' => 'gifu', '静岡県' => 'shizuoka', '愛知県' => 'aichi', '三重県' => 'mie',
        '滋賀県' => 'shiga', '京都府' => 'kyoto', '大阪府' => 'osaka', '兵庫県' => 'hyogo',
        '奈良県' => 'nara', '和歌山県' => 'wakayama', '鳥取県' => 'tottori', '島根県' => 'shimane',
        '岡山県' => 'okayama', '広島県' => 'hiroshima', '山口県' => 'yamaguchi', '徳島県' => 'tokushima',
        '香川県' => 'kagawa', '愛媛県' => 'ehime', '高知県' => 'kochi', '福岡県' => 'fukuoka',
        '佐賀県' => 'saga', '長崎県' => 'nagasaki', '熊本県' => 'kumamoto', '大分県' => 'oita',
        '宮崎県' => 'miyazaki', '鹿児島県' => 'kagoshima', '沖縄県' => 'okinawa',
    ];

    protected function casts(): array
    {
        return [
            'has_prize' => 'boolean',
            'has_purikura' => 'boolean',
            'has_capsule' => 'boolean',
            'lat' => 'float',
            'lng' => 'float',
            'hours' => 'array',
            'games' => 'array',
            'features' => 'array',
            'confirmed_on' => 'date',
        ];
    }

    public static function slugForPrefecture(string $prefecture): ?string
    {
        return self::PREFECTURE_SLUGS[$prefecture] ?? null;
    }

    public static function prefectureForSlug(string $slug): ?string
    {
        return array_search($slug, self::PREFECTURE_SLUGS, true) ?: null;
    }

    public function getPrefectureSlugAttribute(): ?string
    {
        return $this->prefecture ? self::slugForPrefecture($this->prefecture) : null;
    }

    /** 出典つきで編集部が登録した店舗か（＝利用者の投稿ではないか）。 */
    public function getIsOfficialAttribute(): bool
    {
        return filled($this->source_url);
    }

    /** 曜日ごとに同じ時間なら1行にまとめた営業時間。 */
    public function getHoursSummaryAttribute(): ?string
    {
        $hours = collect($this->hours ?? [])
            ->map(fn ($row) => trim(($row['opens'] ?? '').'〜'.($row['closes'] ?? '')))
            ->filter(fn (string $range) => $range !== '〜')
            ->unique()
            ->values();

        return $hours->count() === 1 ? $hours->first() : null;
    }

    public function votes()
    {
        return $this->hasMany(LocationVote::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeOfficial($query)
    {
        return $query->whereNotNull('source_url');
    }
}
