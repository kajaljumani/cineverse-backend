<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'tmdb_id',
        'type',
        'title',
        'poster_path',
        'backdrop_path',
        'genres',
        'rating',
        'release_date',
        'providers',
        'popularity',
    ];

    protected $casts = [
        'genres' => 'array',
        'providers' => 'array',
        'release_date' => 'date',
    ];

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
