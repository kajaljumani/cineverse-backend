<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_id' => 'required|exists:media,id',
            'type' => 'required|in:like,dislike,watched,skipped',
        ]);

        $user = $request->user();
        $mediaId = $validated['media_id'];
        $type = $validated['type'];

        // Record Interaction (Idempotent)
        $interaction = $user->interactions()->updateOrCreate(
            ['media_id' => $mediaId],
            ['type' => $type]
        );

        // Handle Side Effects
        if ($type === 'like') {
            $user->watchlist()->firstOrCreate(['media_id' => $mediaId]);
        } elseif ($type === 'watched') {
            $user->watchlist()->updateOrCreate(
                ['media_id' => $mediaId],
                ['watched_at' => now()]
            );
        } elseif ($type === 'dislike') {
            // Remove from watchlist if disliked?
            $user->watchlist()->where('media_id', $mediaId)->delete();
        }

        return response()->json([
            'message' => 'Interaction recorded',
            'interaction' => $interaction
        ]);
    }
}
