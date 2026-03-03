@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
<div class="flex flex-col space-y-8">
    <!-- Header Actions -->
    <div class="flex flex-wrap items-center justify-between gap-6">
        <div class="bg-card p-6 rounded-3xl border border-white/5 flex-1 min-w-[300px]">
            <h3 class="text-xl font-black text-white mb-2">Sync with TMDB</h3>
            <p class="text-sm text-[#cdbad6] mb-6">Fetch the latest trending movies and series directly from The Movie Database API to update your catalog.</p>
            <form action="{{ route('admin.media.sync') }}" method="POST">
                @csrf
                <button type="submit" class="bg-[#a11cff] hover:bg-[#a11cff]/90 text-white font-bold py-3 px-8 rounded-2xl transition-all shadow-lg shadow-[#a11cff]/20">
                    🚀 Start Catalog Synchronization
                </button>
            </form>
        </div>

        <div class="bg-card p-6 rounded-3xl border border-white/5 flex-1 min-w-[300px]">
            <h3 class="text-xl font-black text-white mb-2">Promotional Notification</h3>
            <p class="text-sm text-[#cdbad6] mb-6">Send a global push notification to all users about new releases or featured content.</p>
            <form action="{{ route('admin.notifications.send') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="title" placeholder="Notification Title..." class="flex-1 bg-[#2a1630] border border-white/5 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                <input type="hidden" name="body" value="Check out the new trending movies on Swipe Scene!">
                <button type="submit" class="bg-pink text-[#1b0d1e] font-bold px-6 rounded-2xl">Send</button>
            </form>
        </div>
    </div>

    <!-- Media Table -->
    <div class="bg-card rounded-3xl border border-white/5 overflow-hidden">
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <h3 class="font-extrabold text-white text-lg">Catalog Overview</h3>
            <form action="{{ route('admin.media.index') }}" method="GET" class="flex items-center bg-[#2a1630] rounded-xl px-4 py-1.5 border border-white/5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search content..." class="bg-transparent border-none focus:outline-none text-xs text-white">
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase tracking-widest text-[#cdbad6] border-b border-white/5 bg-white/2">
                        <th class="px-6 py-4 font-bold">Content Info</th>
                        <th class="px-6 py-4 font-bold">Type</th>
                        <th class="px-6 py-4 font-bold">Release Date</th>
                        <th class="px-6 py-4 font-bold">Popularity Score</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($media as $item)
                    <tr class="group hover:bg-white/5 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-16 rounded-xl bg-white/5 flex-shrink-0 overflow-hidden">
                                     @if($item->poster_path)
                                         <img src="https://image.tmdb.org/t/p/w200{{ $item->poster_path }}" class="w-full h-full object-cover">
                                     @else
                                         <div class="w-full h-full flex items-center justify-center text-[10px]">N/A</div>
                                     @endif
                                </div>
                                <div class="max-w-xs">
                                    <p class="text-sm font-bold text-white truncate">{{ $item->title }}</p>
                                    <p class="text-[10px] text-[#cdbad6] line-clamp-2 opacity-50">{{ $item->overview }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-extrabold px-2 py-1 rounded-lg uppercase tracking-widest {{ $item->type === 'movie' ? 'bg-blue-500/10 text-blue-400' : 'bg-yellow-500/10 text-yellow-400' }}">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#cdbad6] font-medium">
                            {{ $item->release_date }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-white">{{ number_format($item->popularity, 1) }}</span>
                                <div class="w-20 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-pink" style="width: {{ min($item->popularity / 10, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                             <button class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-[#cdbad6] transition-colors">👁️</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-white/5 bg-white/2">
            {{ $media->links() }}
        </div>
    </div>
</div>
@endsection
