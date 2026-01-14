<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author',
        'author_id',
        'published'
    ];

    protected $appends = 
        [
            'created_at_formated',
            'updated_at_formated'
        ];
    

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected function createdAtFormated(): Attribute {
        return Attribute::make(
            get:fn() => $this -> created_at?->diffForHumans()
        );
    }
    protected function updatedAtFormated(): Attribute {
        return Attribute::make(
            get:fn() => $this -> updated_at?->diffForHumans()
        );
    }
}
