<?php

namespace App\Services;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Cache;

class FeedService
{
    protected $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Get the personalized feed for the "Home Swipe" feature.
     * Applies user preferences and excludes interacted media.
     * Automatically fetches more data from TMDB if local supply is exhausted.
     */
    public function getSwipeFeed(User $user, int $perPage = 10)
    {
        $paginator = $this->buildSwipeQuery($user)->paginate($perPage);

        // If the feed is empty (or we're deep in pagination with sparse results), try to fetch more data
        if ($paginator->isEmpty()) {
            // Fetch dynamically from TMDB
            $this->tmdbService->importFromTMDB($user);

            // Re-run the query to include newly fetched items
            // We use a fresh query builderInstance
            $paginator = $this->buildSwipeQuery($user)->paginate($perPage);
        }

        return $paginator;
    }

    /**
     * Build the base query for the swipe feed.
     */
    private function buildSwipeQuery(User $user): Builder
    {
        $preferences = $user->preferences;
        
        // Get IDs of media the user has already interacted with
        $interactedMediaIds = $user->interactions()->pluck('media_id');

        $query = Media::query()->withUserStatus($user);

        // Exclude interacted media
        $query->whereNotIn('id', $interactedMediaIds);
        
        // Include counts
        $query->withCounts();

        // Apply User Preferences
        if ($preferences) {
            if ($preferences->min_rating) {
                $query->where('rating', '>=', $preferences->min_rating);
            }

            if ($preferences->release_year_start) {
                $query->whereYear('release_date', '>=', $preferences->release_year_start);
            }

            if ($preferences->release_year_end) {
                $query->whereYear('release_date', '<=', $preferences->release_year_end);
            }

            // Genre filtering
            if (!empty($preferences->genres)) {
                 $query->where(function (Builder $q) use ($preferences) {
                     foreach ($preferences->genres as $genreId) {
                         $q->orWhereJsonContains('genres', $genreId);
                     }
                 });
            }
        }

        // Sorting (Rule-based)
        $query->orderByDesc('popularity')
              ->orderByDesc('rating')
              ->orderByDesc('id'); // Tie-breaker

        return $query;
    }

    /**
     * Get the global feed (non-personalized).
     * Returns sections: Trending, Latest, Random.
     * Uses caching for static sections.
     */
    public function getGlobalFeed(User $user = null)
    {
        // Versioned cache keys to ensure fresh structure with counts
        $trending = Cache::remember('feed_trending_v2', 3600, function () {
            return Media::query()
                ->withCounts()
                ->orderByDesc('popularity')
                ->limit(10)
                ->get();
        });

        $latest = Cache::remember('feed_latest_v2', 3600, function () {
            return Media::query()
                ->withCounts()
                ->orderByDesc('release_date')
                ->limit(10)
                ->get();
        });

        // Random changes per request, no cache
        $random = Media::query()
            ->withCounts()
            ->inRandomOrder()
            ->limit(10)
            ->get();

        if ($user) {
            $trending = $this->hydrateUserStatus($trending, $user);
            $latest = $this->hydrateUserStatus($latest, $user);
            $random = $this->hydrateUserStatus($random, $user);
        }

        return [
            'trending' => $trending,
            'latest' => $latest,
            'random' => $random,
        ];
    }

    private function hydrateUserStatus($mediaCollection, User $user)
    {
        if ($mediaCollection->isEmpty()) {
            return $mediaCollection;
        }

        $mediaIds = $mediaCollection->pluck('id');

        $watchlistIds = $user->watchlist()
            ->whereIn('media_id', $mediaIds)
            ->pluck('media_id')
            ->flip(); // Key by ID for fast lookup

        $interactions = $user->interactions()
            ->whereIn('media_id', $mediaIds)
            ->pluck('type', 'media_id');

        foreach ($mediaCollection as $media) {
            $media->is_in_watchlist = isset($watchlistIds[$media->id]);
            $media->user_interaction_status = $interactions[$media->id] ?? null;
        }

        return $mediaCollection;
    }
}
