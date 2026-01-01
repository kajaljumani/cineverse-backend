<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - Cineverse</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-900 text-gray-100 font-sans antialiased">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-8">
            <a href="/" class="text-purple-400 hover:text-purple-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Home
            </a>
        </div>

        <h1 class="text-4xl font-bold mb-8 text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600">Privacy Policy</h1>
        
        <div class="prose prose-invert max-w-none">
            <p class="text-lg text-gray-300 mb-6">Last updated: January 1, 2026</p>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">1. Introduction</h2>
                <p class="text-gray-400">Welcome to Cineverse. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our application and tell you about your privacy rights and how the law protects you.</p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">2. Data We Collect</h2>
                <p class="text-gray-400 mb-2">We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:</p>
                <ul class="list-disc pl-5 text-gray-400 space-y-2">
                    <li><strong>Identity Data:</strong> includes first name, last name, username or similar identifier.</li>
                    <li><strong>Contact Data:</strong> includes email address.</li>
                    <li><strong>Technical Data:</strong> includes internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform.</li>
                    <li><strong>Usage Data:</strong> includes information about how you use our app, such as movie preferences, watchlists, and interactions (likes/dislikes).</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">3. How We Use Your Data</h2>
                <p class="text-gray-400">We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
                <ul class="list-disc pl-5 text-gray-400 mt-2 space-y-2">
                    <li>To provide the personalized movie discovery service.</li>
                    <li>To manage your account and preferences.</li>
                    <li>To improve our recommendation algorithms.</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">4. Data Security</h2>
                <p class="text-gray-400">We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed.</p>
            </section>

            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-white">5. Contact Us</h2>
                <p class="text-gray-400">If you have any questions about this privacy policy or our privacy practices, please contact us at: support@cineverse.app</p>
            </section>
        </div>
        
        <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; 2026 Cineverse. All rights reserved.
        </div>
    </div>
</body>
</html>
