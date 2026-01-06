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
        // Uses updateOrCreate to ensure a user only has one interaction state per media item.
        // If they change their mind (e.g., like -> dislike), it updates the existing record.
        $interaction = $user->interactions()->updateOrCreate(
            ['media_id' => $mediaId],
            ['type' => $type]
        );

        // Handle Side Effects
        // Note: 'like' does NOT add to watchlist anymore (per new requirements).
        if ($type === 'watched') {
            $user->watchlist()->updateOrCreate(
                ['media_id' => $mediaId],
                ['watched_at' => now()]
            );
        } elseif ($type === 'dislike') {
            // If disliked, ensure it's removed from watchlist if it was there previously
            $user->watchlist()->where('media_id', $mediaId)->delete();
        }

        return response()->json([
            'message' => 'Interaction recorded',
            'interaction' => $interaction
        ]);
    }
}
