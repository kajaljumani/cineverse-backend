<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->watchlist()->with('media');

        if ($request->has('watched')) {
            if ($request->boolean('watched')) {
                $query->whereNotNull('watched_at');
            } else {
                $query->whereNull('watched_at');
            }
        }

        $watchlist = $query->paginate(20);
        return response()->json($watchlist);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_id' => 'required|exists:media,id',
        ]);
    
        $user = $request->user();
        $mediaId = $validated['media_id'];
    
        // Check for duplicates
        $existing = $user->watchlist()->where('media_id', $mediaId)->first();
    
        if ($existing) {
            return response()->json([
                'message' => 'Media already in watchlist',
                'data' => $existing
            ], 200);
        }
    
        $watchlist = $user->watchlist()->create([
            'media_id' => $mediaId,
            'watched_at' => now(), // Default to watched if added via swipe right? 
                                   // Actually in the app, Swipe Right = Like (Interaction) + Add to Watchlist (Watchlist).
                                   // But user said "I added 10 horror movies".
        ]);
        
        // Award badges for watchlist count
        $badge1 = app(\App\Services\BadgeService::class)->checkAndAward($user, 'watchlist_count');
        // If we treat adding as "watched" (as store() currently sets watched_at = now())
        $badge2 = app(\App\Services\BadgeService::class)->checkAndAward($user, 'genre_watch', $watchlist->media->genres);
        $badge3 = app(\App\Services\BadgeService::class)->checkAndAward($user, 'watch_count', $watchlist->media->type);
    
        return response()->json([
            'watchlist' => $watchlist,
            'newly_unlocked' => $badge1 ?: ($badge2 ?: $badge3)
        ], 201);
    }

    public function destroy(Request $request, $mediaId)
    {
        $deleted = $request->user()->watchlist()->where('media_id', $mediaId)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Removed from watchlist']);
        }

        return response()->json(['message' => 'Item not found in watchlist'], 404);
    }

    public function markWatched(Request $request, $mediaId)
    {
        $watchlist = $request->user()->watchlist()->where('media_id', $mediaId)->first();

        if ($watchlist) {
            $watchlist->update(['watched_at' => now()]);
            
            // Also update interaction
            $request->user()->interactions()->updateOrCreate(
                ['media_id' => $mediaId],
                ['type' => 'watched']
            );

            // Award badges
            $badge1 = app(\App\Services\BadgeService::class)->checkAndAward($request->user(), 'genre_watch', $watchlist->media->genres);
            $badge2 = app(\App\Services\BadgeService::class)->checkAndAward($request->user(), 'watch_count', $watchlist->media->type);

            return response()->json([
                'message' => 'Marked as watched',
                'newly_unlocked' => $badge1 ?: $badge2
            ]);
        }

        return response()->json(['message' => 'Item not found in watchlist'], 404);
    }
}
