<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCenter extends Model
{
    protected $fillable = [
        'name',
        'description',
        'lat',
        'lng',
        'has_prize',
        'has_purikura',
        'has_capsule',
    ];

    protected function casts(): array
    {
        return [
            'has_prize' => 'boolean',
            'has_purikura' => 'boolean',
            'has_capsule' => 'boolean',
        ];
    }

    public function votes()
    {
        return $this->hasMany(LocationVote::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
