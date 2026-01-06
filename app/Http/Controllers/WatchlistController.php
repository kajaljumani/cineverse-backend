<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index(Request $request)
    {
        $watchlist = $request->user()->watchlist()->with('media')->paginate(20);
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
            'media_id' => $mediaId
        ]);

        return response()->json($watchlist, 201);
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

            return response()->json(['message' => 'Marked as watched']);
        }

        return response()->json(['message' => 'Item not found in watchlist'], 404);
    }
}
