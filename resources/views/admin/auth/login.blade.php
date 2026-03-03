@extends('admin.layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md">
        <div class="bg-card p-10 rounded-3xl border border-white/5 shadow-2xl shadow-pink">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-[#a11cff] mb-6 shadow-xl shadow-[#a11cff]/20">
                    <span class="text-4xl text-white">🎬</span>
                </div>
                <h1 class="text-4xl font-extrabold text-white tracking-tight">SWIPE<span class="text-pink">SCENE</span></h1>
                <p class="text-[#cdbad6] mt-2 font-medium">Administrator Login</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[#cdbad6] mb-2 px-1">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-[#2a1630] border border-white/5 rounded-2xl px-5 py-4 text-white placeholder-[#cdbad6]/30 focus:outline-none focus:border-pink/50 transition-all font-medium" placeholder="admin@swipescene.com">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-[#cdbad6] mb-2 px-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-[#2a1630] border border-white/5 rounded-2xl px-5 py-4 text-white placeholder-[#cdbad6]/30 focus:outline-none focus:border-pink/50 transition-all font-medium" placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-pink hover:bg-pink/90 text-[#1b0d1e] font-extrabold py-5 rounded-2xl transition-all transform active:scale-[0.98] shadow-lg shadow-pink/30 text-lg uppercase tracking-tight">
                        Enter Workspace
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center mt-8 text-[#cdbad6] text-sm">
            Swipe Scene Admin v1.0 • Authorized Personnel Only
        </p>
    </div>
</div>
@endsection
