<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cineverse - Your Personal Cinema Guide</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                dark: '#1a1a2e',
                                purple: '#d946ef',
                                pink: '#a855f7',
                            }
                        },
                        animation: {
                            'float': 'float 6s ease-in-out infinite',
                            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
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
    @endif

    <style>
        .gradient-text {
            background: linear-gradient(to right, #d946ef, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #d946ef 0%, #a855f7 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Slider Styles */
        .slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
        }
        .slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            flex-shrink: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .dot {
            height: 10px;
            width: 10px;
            background-color: rgba(255,255,255,0.3);
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .dot.active {
            background-color: #d946ef;
            transform: scale(1.2);
        }
    </style>
</head>
<body class="bg-[#1a1a2e] text-white font-sans antialiased overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card border-b-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight">Cineverse</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#features" class="hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Features</a>
                        <a href="#download" class="hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Download</a>
                        <a href="/privacy" class="hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Privacy</a>
                    </div>
                </div>
                <div>
                    <a href="#download" class="gradient-bg text-white px-4 py-2 rounded-full text-sm font-medium hover:opacity-90 transition-opacity shadow-lg shadow-purple-500/30">
                        Get App
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <!-- Background Blobs -->
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 animate-fade-in-up">
                Discover Your Next <br />
                <span class="gradient-text">Favorite Movie</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-400 mb-10 animate-fade-in-up" style="animation-delay: 0.2s">
                Swipe, match, and watch. Cineverse is your personal cinema guide waiting to be discovered.
            </p>
            
            <div class="flex justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s">
                <button class="gradient-bg text-white px-8 py-3 rounded-full font-bold text-lg shadow-lg shadow-purple-500/30 hover:scale-105 transition-transform flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.21-1.96 1.07-3.11-1.04.05-2.29.69-3.02 1.55-.67.8-1.26 2.09-1.11 3.17 1.16.09 2.34-.73 3.06-1.61z" />
                    </svg>
                    App Store
                </button>
                <button class="glass-card text-white px-8 py-3 rounded-full font-bold text-lg hover:bg-white/10 transition-colors flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.413-.183.999.999 0 0 1 .413-1.631L12.208 12 3.609 3.448a.999.999 0 0 1-.413-1.631.996.996 0 0 1 .413-.183z" opacity="0"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.954 8.32l-6.26-3.613-9.52-5.5A1.76 1.76 0 0 0 3.61 1.815L13.792 12 3.61 22.185a1.77 1.77 0 0 0 1.565 2.61 1.77 1.77 0 0 0 .91-.25l9.52-5.5 6.26-3.612a1.78 1.78 0 0 0 0-3.082zM14.695 12L4.512 1.815 14.695 7.7l6.26 3.612-6.26 3.613-10.183 5.887L14.695 12z" />
                    </svg>
                    Google Play
                </button>
            </div>
        </div>
    </div>

    <!-- Feature Slider Section -->
    <div id="features" class="py-20 bg-[#131322]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Why Cineverse?</h2>
                <div class="w-20 h-1 gradient-bg mx-auto rounded-full"></div>
            </div>

            <div class="relative max-w-4xl mx-auto glass-card rounded-2xl shadow-2xl border border-white/5 overflow-hidden">
                <div class="slider-container" id="featureSlider">
                    <div class="slider-track" id="sliderTrack">
                        <!-- Slide 1 -->
                        <div class="slide">
                            <div class="w-20 h-20 rounded-full bg-purple-500/20 flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Smart Discovery</h3>
                            <p class="text-gray-400 text-center max-w-md">
                                Our intelligent algorithm learns from your taste. The more you swipe, the better recommendations you get.
                            </p>
                        </div>
                        <!-- Slide 2 -->
                        <div class="slide">
                            <div class="w-20 h-20 rounded-full bg-pink-500/20 flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Swipe to Like</h3>
                            <p class="text-gray-400 text-center max-w-md">
                                Effortlessly build your watchlist. Swipe right to like, left to pass. It's that simple.
                            </p>
                        </div>
                        <!-- Slide 3 -->
                        <div class="slide">
                            <div class="w-20 h-20 rounded-full bg-blue-500/20 flex items-center justify-center mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Never Miss Out</h3>
                            <p class="text-gray-400 text-center max-w-md">
                                Keep track of everything you want to watch. Get notified when movies drop on your streaming services.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Dots -->
                    <div class="flex justify-center pb-6" id="sliderDots">
                        <span class="dot active" onclick="goToSlide(0)"></span>
                        <span class="dot" onclick="goToSlide(1)"></span>
                        <span class="dot" onclick="goToSlide(2)"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-8 rounded-2xl hover:bg-white/5 transition-colors">
                    <h3 class="text-xl font-bold mb-3 text-purple-400">Curated Lists</h3>
                    <p class="text-gray-400">Hand-picked collections for every mood and genre.</p>
                </div>
                <div class="glass-card p-8 rounded-2xl hover:bg-white/5 transition-colors">
                    <h3 class="text-xl font-bold mb-3 text-pink-400">Social Sharing</h3>
                    <p class="text-gray-400">Share your favorites with friends and see what they're watching.</p>
                </div>
                <div class="glass-card p-8 rounded-2xl hover:bg-white/5 transition-colors">
                    <h3 class="text-xl font-bold mb-3 text-blue-400">Detailed Info</h3>
                    <p class="text-gray-400">Cast, crew, ratings, and trailers all in one place.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0f0f1a] py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl">Cineverse</span>
                </div>
                <div class="flex space-x-6 text-gray-400">
                    <a href="#" class="hover:text-white transition-colors">About</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                    <a href="/privacy" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-600 text-sm">
                &copy; 2026 Cineverse. All rights reserved.
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
            
            // Update dots
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
        setInterval(nextSlide, 5000);
    </script>
</body>
</html>
