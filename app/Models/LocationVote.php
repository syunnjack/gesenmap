<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationVote extends Model
{
    protected $fillable = [
        'game_center_id',
        'ip_hash',
    ];
}
