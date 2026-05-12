<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'my_favorite_subject';

    protected $fillable = [
        'title',
        'image',
        'description',
        'platform',
        'release_year',
        'genre',
    ];
}
