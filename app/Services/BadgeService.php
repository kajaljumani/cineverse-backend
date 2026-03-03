<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Check and award badges for a user based on specific criteria.
     */
    public function checkAndAward(User $user, string $type, $detail = null)
    {
        $potentialBadges = Badge::where('criteria_type', $type)
            ->where(function($query) use ($detail) {
                if ($detail) {
                    $query->where('criteria_detail', $detail);
                } else {
                    $query->whereNull('criteria_detail');
                }
            })
            ->get();

        $newlyAwarded = null;

        foreach ($potentialBadges as $badge) {
            // Check if user already has this badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            if ($this->isCriteriaMet($user, $badge)) {
                $user->badges()->attach($badge->id);
                if (!$newlyAwarded) {
                    $newlyAwarded = $badge;
                }
            }
        }

        return $newlyAwarded;
    }

    protected function isCriteriaMet(User $user, Badge $badge): bool
    {
        switch ($badge->criteria_type) {
            case 'genre_watch':
                return $user->watchlist()
                    ->whereNotNull('watched_at')
                    ->whereHas('media', function($q) use ($badge) {
                        $q->where('genres', 'like', '%' . $badge->criteria_detail . '%');
                    })
                    ->count() >= $badge->criteria_value;

            case 'social_chat':
                if (!$badge->criteria_detail) {
                    // Total messages
                    return $user->messages()->count() >= $badge->criteria_value;
                } else {
                    // Different chat partners
                    return DB::table('conversations')
                        ->where('user_one_id', $user->id)
                        ->orWhere('user_two_id', $user->id)
                        ->count() >= $badge->criteria_value;
                }

            case 'social_comment':
                return $user->comments()->count() >= $badge->criteria_value;

            case 'watchlist_count':
                return $user->watchlist()->count() >= $badge->criteria_value;

            case 'watch_count':
                // For "Movie Mogul" or "Series Specialist"
                $type = $badge->criteria_detail; // 'movie' or 'tv'
                return $user->watchlist()
                    ->whereNotNull('watched_at')
                    ->whereHas('media', function($q) use ($type) {
                        $q->where('type', $type);
                    })
                    ->count() >= $badge->criteria_value;

            case 'follower_count':
                return $user->followers()->count() >= $badge->criteria_value;

            case 'badge_count':
                return $user->badges()->count() >= $badge->criteria_value;

            default:
                return false;
        }
    }
}
