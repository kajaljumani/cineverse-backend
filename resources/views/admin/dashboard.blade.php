@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stats Cards -->
    <div class="bg-card p-6 rounded-3xl border border-white/5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <span class="text-3xl">👥</span>
            <span class="text-xs font-bold uppercase tracking-widest text-[#cdbad6]">Total Users</span>
        </div>
        <p class="text-4xl font-extrabold text-white">{{ number_format($stats['total_users']) }}</p>
        <p class="text-xs text-pink mt-2 font-medium">+12% from last week</p>
    </div>

    <div class="bg-card p-6 rounded-3xl border border-white/5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <span class="text-3xl">💎</span>
            <span class="text-xs font-bold uppercase tracking-widest text-[#cdbad6]">Total Interactions</span>
        </div>
        <p class="text-4xl font-extrabold text-white">{{ number_format($stats['total_interactions']) }}</p>
        <p class="text-xs text-pink mt-2 font-medium">New match every 4 mins</p>
    </div>

    <div class="bg-card p-6 rounded-3xl border border-white/5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <span class="text-3xl">📌</span>
            <span class="text-xs font-bold uppercase tracking-widest text-[#cdbad6]">Watchlist Items</span>
        </div>
        <p class="text-4xl font-extrabold text-white">{{ number_format($stats['total_watchlist']) }}</p>
        <p class="text-xs text-[#cdbad6] mt-2 font-medium">Trending high this month</p>
    </div>

    <div class="bg-card p-6 rounded-3xl border border-white/5 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <span class="text-3xl">🎬</span>
            <span class="text-xs font-bold uppercase tracking-widest text-[#cdbad6]">Media Library</span>
        </div>
        <p class="text-4xl font-extrabold text-white">{{ number_format($stats['total_media']) }}</p>
        <p class="text-xs text-green-400 mt-2 font-medium">Fully synced with TMDB</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Users -->
    <div class="lg:col-span-2 bg-card rounded-3xl border border-white/5 overflow-hidden">
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-extrabold text-white">Recent Activations</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-pink uppercase tracking-widest hover:underline">View All</a>
        </div>
        <div class="p-0">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase tracking-widest text-[#cdbad6] border-b border-white/5">
                        <th class="px-6 py-4 font-bold">User</th>
                        <th class="px-6 py-4 font-bold">Joined</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($stats['recent_users'] as $user)
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-pink/10 flex items-center justify-center border border-pink/20 text-pink font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-[#cdbad6] opacity-60">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#cdbad6]">{{ $user->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_blocked)
                                <span class="bg-red-500/10 text-red-500 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-tighter">Blocked</span>
                            @else
                                <span class="bg-green-500/10 text-green-500 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-tighter">Active</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Media -->
    <div class="bg-card rounded-3xl border border-white/5 overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="font-extrabold text-white">🔥 Most Popular</h3>
        </div>
        <div class="p-6 space-y-6">
            @foreach($top_media as $media)
            <div class="flex items-start space-x-4 group cursor-pointer">
                <div class="w-16 h-20 rounded-xl bg-white/10 flex-shrink-0 relative overflow-hidden">
                     @if($media->poster_path)
                         <img src="https://image.tmdb.org/t/p/w200{{ $media->poster_path }}" class="w-full h-full object-cover">
                     @else
                         <div class="w-full h-full flex items-center justify-center text-xs">NO IMG</div>
                     @endif
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-white leading-tight mb-1 group-hover:text-pink transition-colors">{{ $media->title }}</h4>
                    <p class="text-xs text-[#cdbad6] mb-2">{{ $media->type }} • {{ $media->release_date }}</p>
                    <div class="flex items-center text-[10px] font-bold text-pink space-x-1">
                        <span>❤️</span>
                        <span>{{ $media->watchlist_count }} PEOPLE WATCHED</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
