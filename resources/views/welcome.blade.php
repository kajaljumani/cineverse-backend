<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SwipeScene - Your Ultimate Cinematic Discovery Guide</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0f0f1a',
                            purple: '#d946ef',
                            pink: '#a855f7',
                            deep: '#131322',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #0f0f1a; scroll-behavior: smooth; }
        .gradient-text {
            background: linear-gradient(to right, #d946ef, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #d946ef 0%, #a855f7 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .hero-glow {
            background: radial-gradient(circle at 50% 50%, rgba(217, 70, 239, 0.15) 0%, transparent 70%);
        }
        
        /* Slider Styles */
        .slider-container { overflow: hidden; position: relative; width: 100%; }
        .slider-track { display: flex; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
        .slide { min-width: 100%; flex-shrink: 0; padding: 3rem 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .dot { height: 8px; width: 8px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: inline-block; margin: 0 6px; cursor: pointer; transition: all 0.3s; }
        .dot.active { background-color: #d946ef; width: 24px; border-radius: 4px; }
        
        /* Mockup Frame */
        .mockup-frame {
            border: 8px solid #1a1a2e;
            border-radius: 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 280px;
            margin: 0 auto;
            overflow: hidden;
            background: #1a1a2e;
        }
    </style>
</head>
<body class="text-white font-sans antialiased overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card border-b-0 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3 group cursor-pointer" onclick="window.scrollTo(0,0)">
                    <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center transform group-hover:scale-110 transition-transform shadow-lg shadow-purple-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h15a3 3 0 003-3v-9a3 3 0 00-3-3h-15zm0 1.5h15A1.5 1.5 0 0121 7.5v9a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6zM9 9a1 1 0 00-1 1v4a1 1 0 002 0v-4a1 1 0 00-1-1zm6 0a1 1 0 00-1 1v4a1 1 0 002 0v-4a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-2xl tracking-tighter">SWIPESCENE</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        <a href="#features" class="text-gray-300 hover:text-white transition-colors font-medium">Features</a>
                        <a href="#showcase" class="text-gray-300 hover:text-white transition-colors font-medium">Showcase</a>
                        <a href="#download" class="text-gray-300 hover:text-white transition-colors font-medium">Get App</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="mailto:info@galaxywebservices.in" class="hidden sm:block text-sm text-gray-400 hover:text-white transition-colors">Support</a>
                    <a href="#download" class="gradient-bg text-white px-6 py-2.5 rounded-full text-sm font-bold hover:opacity-90 transition-all hover:scale-105 shadow-xl shadow-purple-500/20">
                        Download
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-glow">
        <!-- Background Decor -->
        <div class="absolute top-1/4 -right-20 w-96 h-96 bg-purple-600/10 rounded-full filter blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-pink-600/10 rounded-full filter blur-[100px] animate-pulse-slow" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 animate-fade-in-up">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <span class="text-xs font-bold tracking-widest uppercase text-gray-400">Now Available for Beta Testing</span>
                    </div>
                    <h1 class="text-6xl md:text-8xl font-black leading-[0.9] mb-8 animate-fade-in-up" style="animation-delay: 0.1s">
                        REDEFINE YOUR <br />
                        <span class="gradient-text">DISCOVERY</span>
                    </h1>
                    <p class="text-xl text-gray-400 max-w-xl mb-12 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
                        Swipe through thousands of movies and series. Join a community of cinephiles, share your favorites, and never wonder what to watch again.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.3s">
                        <a href="#download" class="glass-card flex items-center gap-3 px-8 py-4 rounded-2xl hover:bg-white/10 transition-all group border border-white/10">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-8">
                        </a>
                        <div class="relative group">
                            <div class="absolute -top-3 -right-3 z-20">
                                <span class="bg-gray-800 text-[10px] font-black px-2 py-0.5 rounded-full border border-white/20 text-white uppercase tracking-tighter shadow-lg">Coming Soon</span>
                            </div>
                            <div class="glass-card flex items-center gap-3 px-8 py-4 rounded-2xl opacity-50 cursor-not-allowed border border-white/10 grayscale">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.21-1.96 1.07-3.11-1.04.05-2.29.69-3.02 1.55-.67.8-1.26 2.09-1.11 3.17 1.16.09 2.34-.73 3.06-1.61z" />
                                </svg>
                                <div class="text-left">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase leading-none">Download on the</p>
                                    <p class="text-xl font-bold leading-none">App Store</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12 flex items-center gap-6 text-gray-500 animate-fade-in-up" style="animation-delay: 0.4s">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full border-2 border-brand-dark bg-purple-500 flex items-center justify-center font-bold text-white text-xs">AJ</div>
                            <div class="w-10 h-10 rounded-full border-2 border-brand-dark bg-pink-500 flex items-center justify-center font-bold text-white text-xs">TU</div>
                            <div class="w-10 h-10 rounded-full border-2 border-brand-dark bg-blue-500 flex items-center justify-center font-bold text-white text-xs">BK</div>
                        </div>
                        <p class="text-sm font-medium"><span class="text-white">5,000+</span> movie lovers waiting for you.</p>
                    </div>
                </div>
                
                <div class="hidden lg:block relative p-12">
                     <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/20 to-pink-500/20 rounded-full filter blur-[80px] animate-pulse"></div>
                     <div class="relative transform rotate-6 hover:rotate-0 transition-transform duration-700">
                        <div class="mockup-frame">
                             <img src="{{ asset('images/screenshots/app_swipe.png') }}" alt="App Swipe View" class="w-full">
                        </div>
                        <div class="absolute -bottom-10 -right-20 transform -rotate-12 hover:rotate-0 transition-transform duration-700 delay-100 z-20">
                            <div class="mockup-frame !max-w-[240px] border-4">
                                <img src="{{ asset('images/screenshots/app_home.png') }}" alt="App Home View" class="w-full">
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Slider -->
    <div id="features" class="py-32 bg-brand-deep relative border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
                <div class="text-left">
                    <h2 class="text-4xl md:text-5xl font-black mb-4">ENGINEERED FOR <br /><span class="gradient-text">ENTERTAINMENT</span></h2>
                    <p class="text-gray-400">Everything you need to find your next obsession.</p>
                </div>
                <!-- Dots for Slider - Positioned for UI feel -->
                <div class="flex justify-center mb-4" id="sliderDots">
                    <span class="dot active" onclick="goToSlide(0)"></span>
                    <span class="dot" onclick="goToSlide(1)"></span>
                    <span class="dot" onclick="goToSlide(2)"></span>
                </div>
            </div>

            <div class="slider-container rounded-3xl overflow-hidden glass-card">
                <div class="slider-track" id="sliderTrack">
                    <!-- Slide 1 -->
                    <div class="slide">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="text-left space-y-6">
                                <div class="w-16 h-16 rounded-2xl gradient-bg flex items-center justify-center shadow-lg shadow-purple-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                    </svg>
                                </div>
                                <h3 class="text-4xl font-bold">Smart Discovery</h3>
                                <p class="text-xl text-gray-400 leading-relaxed">
                                    Stop scrolling aimlessly. Our advanced AI learns from every swipe to curate a personalized theater experience just for you.
                                </p>
                                <ul class="space-y-3 text-gray-300">
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Genre-based filtering
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Watchlist integration
                                    </li>
                                </ul>
                            </div>
                            <div class="relative flex justify-center">
                                <div class="glass-card p-4 rounded-[42px] border-4 border-white/5">
                                    <div class="mockup-frame !max-w-[220px] !border-0 !rounded-[24px]">
                                         <img src="{{ asset('images/screenshots/app_home.png') }}" alt="Discovery View" class="w-full">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="slide">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="order-2 md:order-1 relative flex justify-center">
                                <div class="glass-card p-4 rounded-[42px] border-4 border-white/5">
                                    <div class="mockup-frame !max-w-[220px] !border-0 !rounded-[24px]">
                                         <img src="{{ asset('images/screenshots/app_swipe.png') }}" alt="Swipe View" class="w-full">
                                    </div>
                                </div>
                            </div>
                            <div class="order-1 md:order-2 text-left space-y-6">
                                <div class="w-16 h-16 rounded-2xl bg-pink-600 flex items-center justify-center shadow-lg shadow-pink-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </div>
                                <h3 class="text-4xl font-bold">Swipe to Like</h3>
                                <p class="text-xl text-gray-400 leading-relaxed">
                                    Finding movies should be fun. Swipe right to add to your favorites, swipe left to discover something else. It's cinematic match-making.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="slide">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="text-left space-y-6">
                                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a5.97 5.97 0 00-.94 3.197M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zm1.125 18a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-4xl font-bold">Social Sharing</h3>
                                <p class="text-xl text-gray-400 leading-relaxed">
                                    SwipeScene is better with buddies. Connect with friends, see what they're liking, and chat about your next movie night right in the app.
                                </p>
                            </div>
                            <div class="relative flex h-80 justify-center items-center">
                                <div class="glass-card p-12 rounded-full border-4 border-white/5 animate-float flex items-center justify-center overflow-hidden">
                                    <div class="flex -space-x-8">
                                        <div class="w-24 h-24 rounded-full border-4 border-brand-dark gradient-bg flex items-center justify-center text-2xl font-black">AJ</div>
                                        <div class="w-24 h-24 rounded-full border-4 border-brand-dark bg-pink-600 flex items-center justify-center text-2xl font-black">TU</div>
                                        <div class="w-24 h-24 rounded-full border-4 border-brand-dark bg-blue-600 flex items-center justify-center text-2xl font-black">BK</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Section -->
    <div id="download" class="py-32 relative text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
             <h2 class="text-5xl md:text-7xl font-black mb-8">JOIN THE <span class="gradient-text tracking-tighter">SCENE</span></h2>
             <p class="text-xl text-gray-400 mb-12">Your pocket theater is just one click away. Start your discovery journey today.</p>
             <div class="flex justify-center gap-6">
                <!-- Actual Google Play Link mockup -->
                <a href="#" class="gradient-bg px-10 py-5 rounded-[2rem] font-black text-xl hover:scale-105 transition-all shadow-2xl shadow-purple-500/40">
                    DOWNLOAD NOW
                </a>
             </div>
             <p class="mt-8 text-gray-600 font-medium">Compatible with Android 8.0+ and iOS 14.0+</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0b0b14] py-20 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M4.5 4.5a3 3 0 00-3 3v9a3 3 0 003 3h15a3 3 0 003-3v-9a3 3 0 00-3-3h-15zm0 1.5h15A1.5 1.5 0 0121 7.5v9a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z" />
                            </svg>
                        </div>
                        <span class="font-extrabold text-2xl tracking-tighter leading-none">SWIPESCENE</span>
                    </div>
                    <p class="text-gray-500 max-w-sm leading-relaxed">
                        Cineverse is a project by Galaxy Web Services. We create premium digital experiences for users worldwide.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-white uppercase tracking-widest text-sm">Platform</h4>
                    <ul class="space-y-4 text-gray-500">
                        <li><a href="#features" class="hover:text-purple-400 transition-colors">Features</a></li>
                        <li><a href="#showcase" class="hover:text-purple-400 transition-colors">Showcase</a></li>
                        <li><a href="#download" class="hover:text-purple-400 transition-colors">Download Beta</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-white uppercase tracking-widest text-sm">Company</h4>
                    <ul class="space-y-4 text-gray-500">
                        <li><a href="/privacy" class="hover:text-purple-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="/terms" class="hover:text-purple-400 transition-colors">Terms of Use</a></li>
                        <li><a href="mailto:info@galaxywebservices.in" class="hover:text-purple-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-gray-600 text-sm">
                    &copy; 2026 SwipeScene. Crafted by <a href="https://galaxywebservices.in" class="text-gray-400 hover:text-purple-400">Galaxy Web Services</a>.
                </p>
                <div class="flex gap-6">
                    <!-- Social placeholders -->
                    <span class="text-gray-600 hover:text-white cursor-pointer transition-colors text-xs font-bold tracking-widest uppercase">Instagram</span>
                    <span class="text-gray-600 hover:text-white cursor-pointer transition-colors text-xs font-bold tracking-widest uppercase">Twitter</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Slider Logic
        let currentSlide = 0;
        const track = document.getElementById('sliderTrack');
        const dots = document.getElementById('sliderDots').children;
        const totalSlides = 3;

        function updateSlider() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            for(let i=0; i<dots.length; i++) {
                dots[i].classList.remove('active');
            }
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
        }

        // Auto advance
        setInterval(nextSlide, 8000);
        
        // Simple reveal on scroll observer if needed, but Tailwind animate-fade-in-up covers hero.
    </script>
</body>
</html>
