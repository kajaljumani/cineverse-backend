<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use App\Models\Badge;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = User::withCount(['followers', 'following'])->findOrFail($id);

        $isFollowing = false;
        if ($request->user()) {
            $isFollowing = $request->user()->isFollowing($user);
        }

        return response()->json([
            'user' => $user,
            'is_following' => $isFollowing,
            'watchlist' => $user->watchlist()->with('media')->latest()->take(10)->get()
        ]);
    }

    public function badges(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isOwner = $request->user() && $request->user()->id == $user->id;

        if ($isOwner) {
            $allBadges = Badge::all();
            $userBadgeIds = $user->badges->pluck('id')->toArray();
            
            $badges = $allBadges->map(function($badge) use ($userBadgeIds) {
                $badge->is_unlocked = in_array($badge->id, $userBadgeIds);
                return $badge;
            });

            return response()->json($badges);
        }

        return response()->json($user->badges);
    }

    public function watchers($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $watchers = $media->watchers()->paginate(20);
        
        return response()->json($watchers);
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        $following = $user->following()->paginate(20);
        return response()->json($following);
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers()->paginate(20);
        return response()->json($followers);
    }
}
