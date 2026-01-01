<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'genres',
        'languages',
        'providers',
        'min_rating',
        'release_year_start',
        'release_year_end',
    ];

    protected $casts = [
        'genres' => 'array',
        'languages' => 'array',
        'providers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
