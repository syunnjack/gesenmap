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


}
