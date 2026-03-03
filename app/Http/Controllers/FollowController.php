<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request, $id)
    {
        $userToFollow = User::findOrFail($id);
        
        if ($request->user()->id === $userToFollow->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }

        $request->user()->following()->syncWithoutDetaching($userToFollow->id);

        // Award badges to the followed user (follower_count)
        $newlyUnlocked = app(\App\Services\BadgeService::class)->checkAndAward($userToFollow, 'follower_count');

        return response()->json([
            'message' => 'Now following ' . $userToFollow->name,
            'newly_unlocked' => $newlyUnlocked
        ]);
    }

    public function unfollow(Request $request, $id)
    {
        $userToUnfollow = User::findOrFail($id);
        $request->user()->following()->detach($userToUnfollow->id);

        return response()->json(['message' => 'Unfollowed ' . $userToUnfollow->name]);
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user->followers()->paginate(20));
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user->following()->paginate(20));
    }
}
