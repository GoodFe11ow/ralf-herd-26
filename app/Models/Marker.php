<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'description'
    ];

    const CREATED_AT =  'added';
    const UPDATED_AT = 'edited';
}