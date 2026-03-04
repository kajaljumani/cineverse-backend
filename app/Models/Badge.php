<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'criteria_type',
        'criteria_value',
        'criteria_detail',
    ];

    /**
     * Get the badge icon URL.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getIconAttribute($value)
    {
        if (!$value || filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps();
    }
}
