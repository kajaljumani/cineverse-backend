<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Swipe Scene</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #130814;
            color: #e4d9f5;
        }
        .bg-card {
            background-color: #1b0d1e;
        }
        .text-pink {
            color: #ff39db;
        }
        .bg-pink {
            background-color: #ff39db;
        }
        .border-pink {
            border-color: #ff39db;
        }
        .shadow-pink {
            box-shadow: 0 0 20px rgba(255, 57, 219, 0.2);
        }
        .sidebar-item:hover {
            background-color: rgba(255, 57, 219, 0.1);
            color: #ff39db;
        }
        .sidebar-item.active {
            background-color: rgba(255, 57, 219, 0.15);
            color: #ff39db;
            border-right: 3px solid #ff39db;
        }
    </style>
</head>
<body class="flex min-h-screen">

    @auth
    <!-- Sidebar -->
    <aside class="w-64 bg-card border-r border-white/5 flex flex-col">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-10">
                <div class="w-10 h-10 rounded-xl bg-[#a11cff] flex items-center justify-center shadow-lg shadow-[#a11cff]/30">
                    <span class="text-xl">🎬</span>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-white">SWIPE<span class="text-pink">SCENE</span></h1>
                    <p class="text-[10px] uppercase tracking-widest text-[#cdbad6] -mt-1 font-semibold">Admin Panel</p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="mr-3">👥</span> Users
                </a>
                <a href="{{ route('admin.media.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <span class="mr-3">🎬</span> Media Library
                </a>
            </nav>
        </div>

        <div class="mt-auto p-6 border-t border-white/5">
            <div class="flex items-center space-x-3 mb-6">
                 <div class="w-10 h-10 rounded-full bg-pink/20 flex items-center justify-center border border-pink/30">
                    <span class="text-pink font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                 </div>
                 <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-[#cdbad6] truncate">Administrator</p>
                 </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-[#cdbad6] hover:text-white transition-colors flex items-center">
                    <span class="mr-3">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        @auth
        <header class="h-16 bg-card/50 backdrop-blur-md border-b border-white/5 px-8 flex items-center justify-between sticky top-0 z-10">
            <h2 class="text-lg font-bold">@yield('title', 'Dashboard')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-[#cdbad6] text-sm">{{ now()->format('l, j F') }}</span>
            </div>
        </header>
        @endauth

        <div class="p-8 flex-1">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
