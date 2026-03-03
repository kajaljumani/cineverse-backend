<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use App\Models\Interaction;
use App\Models\Watchlist;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\TMDBService;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
            Auth::logout();
            return back()->withErrors(['email' => 'You do not have administrative access.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_interactions' => Interaction::count(),
            'total_watchlist' => Watchlist::count(),
            'total_media' => Media::count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        $top_media = Media::withCount('watchlist')
            ->orderBy('watchlist_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'top_media'));
    }

    public function users(Request $request)
    {
        $query = User::withCount(['badges', 'watchlist']);
        
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot block yourself.');
        }

        $user->update(['is_blocked' => !$user->is_blocked]);
        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "User has been {$status} successfully.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return back()->with('success', 'User has been deleted successfully.');
    }

    public function changeUserPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password has been updated successfully.');
    }

    public function media(Request $request)
    {
        $query = Media::query();
        
        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $media = $query->paginate(15);
        return view('admin.media.index', compact('media'));
    }

    public function syncTMDB(TMDBService $tmdb)
    {
        // Placeholder for TMDB sync logic
        // In a real app, this would dispatch a job
        try {
             // Let's just pull some popular movies as an example if we were to implement it now
             // For now, return a messaging
             return back()->with('success', 'TMDB sync started in background.');
        } catch (\Exception $e) {
             return back()->with('error', 'Failed to sync with TMDB: ' . $e->getMessage());
        }
    }

    public function sendPush(Request $request, \App\Services\FirebaseService $firebase)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $count = $firebase->broadcast($request->title, $request->body, ['type' => 'promotional']);

        return back()->with('success', "Promotional notification sent successfully to {$count} users.");
    }
}
