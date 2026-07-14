<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'game_center_id',
        'nickname',
        'rating',
        'comment',
        'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function gameCenter()
    {
        return $this->belongsTo(GameCenter::class);
    }
}
