<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'type',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'genres',
        'rating',
        'release_date',
        'providers',
        'popularity',
        'cast',
        'certification',
        'trailer_key',
        'runtime',
    ];

    protected $casts = [
        'type' => \App\Enums\MediaType::class,
        'genres' => 'array',
        'providers' => 'array',
        'cast' => 'array',
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

    public function scopeWithUserStatus($query, $user)
    {
        if (!$user) {
            return $query->addSelect([
                'is_in_watchlist' => \Illuminate\Database\Query\Expression::make('0'),
                'user_interaction_status' => \Illuminate\Database\Query\Expression::make('null')
            ]);
        }

        return $query->addSelect([
            'is_in_watchlist' => \App\Models\Watchlist::selectRaw('count(*)')
                ->whereColumn('media_id', 'media.id')
                ->where('user_id', $user->id)
                ->limit(1),
            'user_interaction_status' => \App\Models\Interaction::select('type')
                ->whereColumn('media_id', 'media.id')
                ->where('user_id', $user->id)
                ->limit(1)
        ]);
    }

    public function scopeWithCounts($query)
    {
        return $query->withCount('comments')
            ->withCount(['interactions as likes_count' => function ($query) {
                $query->where('type', 'like');
            }]);
    }
}
