<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $preferences = $user->preferences;

        // Get IDs of media the user has already interacted with
        $interactedMediaIds = $user->interactions()->pluck('media_id');

        $query = \App\Models\Media::query();

        // Exclude interacted media
        $query->whereNotIn('id', $interactedMediaIds);

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

            // Genre filtering (simplified: if user has genres, show media that has at least one)
            // This depends on how genres are stored. Assuming JSON array of IDs.
            // SQLite/MySQL JSON support required.
            if (!empty($preferences->genres)) {
                 $query->where(function ($q) use ($preferences) {
                     foreach ($preferences->genres as $genreId) {
                         $q->orWhereJsonContains('genres', $genreId);
                     }
                 });
            }
        }

        // Sorting (Rule-based)
        // For now, simple sort by popularity and rating
        $query->orderByDesc('popularity')
              ->orderByDesc('rating');

        $feed = $query->paginate(10);

        return response()->json($feed);
    }
}
