@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="bg-card rounded-3xl border border-white/5 overflow-hidden">
    <!-- Filters / Search -->
    <div class="p-6 border-b border-white/5 flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center bg-[#2a1630] rounded-2xl px-4 py-2 w-full max-w-md border border-white/5">
            <span class="mr-2">🔎</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user by name or email..." class="bg-transparent border-none focus:outline-none text-sm text-white w-full placeholder-[#cdbad6]/30">
            <button type="submit" class="hidden">Search</button>
        </form>

        <div class="flex items-center space-x-2">
            <span class="text-xs font-bold text-[#cdbad6] uppercase tracking-widest mr-2">Quick Filter:</span>
            <button class="text-[10px] font-extrabold uppercase px-3 py-1 bg-pink text-[#1b0d1e] rounded-full">All Users</button>
            <button class="text-[10px] font-extrabold uppercase px-3 py-1 bg-white/5 text-[#cdbad6] rounded-full hover:bg-white/10 transition-colors">Blocked Only</button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] uppercase tracking-widest text-[#cdbad6] border-b border-white/5 bg-white/2">
                    <th class="px-6 py-4 font-bold">User Information</th>
                    <th class="px-6 py-4 font-bold">Engagement</th>
                    <th class="px-6 py-4 font-bold">Account Status</th>
                    <th class="px-6 py-4 font-bold">Badges</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($users as $user)
                <tr class="group hover:bg-white/5 transition-all">
                    <td class="px-6 py-5">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-2xl bg-pink/10 flex items-center justify-center border border-pink/20 text-pink text-lg font-black shadow-inner">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-base font-bold text-white leading-tight">{{ $user->name }}</p>
                                <p class="text-xs text-[#cdbad6] opacity-60">{{ $user->email }}</p>
                                @if($user->is_admin)
                                    <span class="text-[9px] font-black bg-[#a11cff]/20 text-[#a11cff] border border-[#a11cff]/30 px-1.5 py-0.5 rounded mt-1 inline-block uppercase tracking-widest">Admin</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="space-y-1">
                            <p class="text-xs text-[#cdbad6] font-medium"><span class="text-white font-bold">{{ $user->watchlist_count }}</span> Watchlist</p>
                            <p class="text-xs text-[#cdbad6] font-medium"><span class="text-white font-bold">{{ $user->badges_count }}</span> Badges</p>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @if($user->is_blocked)
                            <div class="flex items-center text-red-500 font-bold text-xs uppercase tracking-tight">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                                Blocked
                            </div>
                        @else
                            <div class="flex items-center text-green-500 font-bold text-xs uppercase tracking-tight">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                Active
                            </div>
                        @endif
                        <p class="text-[10px] text-[#cdbad6] mt-1 opacity-50">Joined {{ $user->created_at->format('M Y') }}</p>
                    </td>
                    <td class="px-6 py-5">
                       <div class="flex -space-x-2">
                           @foreach($user->badges->take(4) as $badge)
                               <div class="w-8 h-8 rounded-full border-2 border-[#1b0d1e] bg-[#2a1630] flex items-center justify-center overflow-hidden" title="{{ $badge->name }}">
                                   @if(str_starts_with($badge->icon, 'http'))
                                       <img src="{{ $badge->icon }}" class="w-full h-full object-contain">
                                   @elseif($badge->icon)
                                       <img src="{{ asset($badge->icon) }}" class="w-full h-full object-contain">
                                   @else
                                       <span class="text-sm">🏅</span>
                                   @endif
                               </div>
                           @endforeach
                           @if($user->badges_count > 4)
                               <div class="w-8 h-8 rounded-full border-2 border-[#1b0d1e] bg-white/10 flex items-center justify-center text-[10px] font-bold text-white">
                                   +{{ $user->badges_count - 4 }}
                               </div>
                           @endif
                       </div>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Toggle Block -->
                            <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-white transition-colors" title="{{ $user->is_blocked ? 'Unblock' : 'Block' }}">
                                    {!! $user->is_blocked ? '🔓' : '🚫' !!}
                                </button>
                            </form>
                            
                            <!-- Delete User -->
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-500 transition-colors" title="Delete Account">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-white/5 bg-white/2">
        {{ $users->links() }}
    </div>
</div>
@endsection
