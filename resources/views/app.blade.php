<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pliant')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Kollektif:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Kollektif', sans-serif;
            line-height: 1.6;
            padding-top: 72px; /* Hauteur de la navbar */
        }

        h1, h2, h3, .button {
            font-family: 'Kollektif', sans-serif;
            font-weight: 700;
        }

        .hero {
            height: 500px;
        }

        @media (max-width: 768px) {
            .hero {
                height: 60vh;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
        }

        .button {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        .nav-link {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #3498db;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Modern Cards */
        .card-modern {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 41, 55, 0.12);
            padding: 2.5rem 2rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-modern:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(37, 99, 235, 0.15);
        }

        /* Section titles */
        .section-title {
            font-size: 2.5rem;
            font-family: 'Kollektif', sans-serif;
            color: #1e3a8a;
            margin-bottom: 2rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        /* Gradient backgrounds */
        .bg-gradient-blue {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #fff;
        }

        /* Call to action */
        .cta {
            background: #fff;
            color: #1e3a8a;
            border-radius: 2rem;
            padding: 2rem 3rem;
            font-size: 1.3rem;
            font-family: 'Kollektif', sans-serif;
            font-weight: 700;
            box-shadow: 0 4px 24px 0 rgba(37, 99, 235, 0.10);
            transition: background 0.3s, color 0.3s, transform 0.3s;
        }
        .cta:hover {
            background: #2563eb;
            color: #fff;
            transform: scale(1.04);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.7s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Utility classes */
        .text-blue-900 { color: #1e3a8a; }
        .text-blue-600 { color: #2563eb; }
        .bg-blue-100 { background: #dbeafe; }
        .bg-blue-900 { background: #1e3a8a; }

        /* Responsive grid for features */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        @media (max-width: 1024px) {
            .feature-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 768px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced navbar blur */
        #navbar {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Fallback for browsers without backdrop-filter support */
        @supports not (backdrop-filter: blur(12px)) {
            #navbar {
                background: rgba(255, 255, 255, 0.95) !important;
            }
        }

        /* Logo styling */
        .navbar-logo {
            height: 60px; /* Increased height for better visibility */
            width: auto; /* Maintain aspect ratio */
            max-width: 100%; /* Ensure it doesn't exceed container */
            transition: transform 0.3s ease;
        }

        .navbar-logo:hover {
            transform: scale(1.1); /* Slight zoom on hover */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar glassmorphism & responsive -->
    <header class="fixed w-full top-0 left-0 z-50">
        <nav class="border-b border-gray-200 shadow-sm" id="navbar">
            <div class="container mx-auto flex justify-between items-center px-4 py-3">
                <img src="{{ asset('images/image.png') }}" alt="Pliant Logo" class="navbar-logo">
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}" class="text-[#1f2937] hover:text-[#2563eb] transition duration-300 {{ Route::currentRouteName() === 'home' ? 'font-semibold underline' : '' }}">Accueil</a>
                    <a href="#features" class="text-[#1f2937] hover:text-[#2563eb] transition duration-300">Fonctionnalités</a>
                    <a href="#" class="text-[#1f2937] hover:text-[#2563eb] transition duration-300">Solutions</a>
                    <a href="#contact" class="text-[#1f2937] hover:text-[#2563eb] transition duration-300">Contact</a>
                    
                </div>
                <a href="{{ route('login') }}" class="bg-slate-200 hover:bg-slate-300 text-[#1f2937] px-5 py-2 rounded-full transition font-kollektif shadow-lg">Connexion</a> 
                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden text-[#1f2937] focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden fixed top-0 left-0 w-full h-full bg-white/90 backdrop-blur-xl z-50 flex flex-col items-center justify-center space-y-8 text-[#1f2937] text-2xl font-kollektif hidden transition-all duration-300">
                <button id="close-mobile-menu" class="absolute top-8 right-8 text-3xl"><i class="fas fa-times"></i></button>
                <a href="{{ route('home') }}" class="hover:text-[#2563eb] transition">Accueil</a>
                <a href="#features" class="hover:text-[#2563eb] transition">Fonctionnalités</a>
                <a href="#" class="hover:text-[#2563eb] transition">Solutions</a>
                <a href="#contact" class="hover:text-[#2563eb] transition">Contact</a>
                <a href="{{ route('login') }}" class="bg-slate-200 hover:bg-slate-300 text-[#1f2937] px-8 py-3 rounded-full transition font-kollektif shadow-lg">Connexion</a>
            </div>
        </nav>
    </header>
    
    <main class="container mx-auto px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-200 px-6 py-12 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 flex justify-center items-center pointer-events-none">
        <div class="w-80 h-80 rounded-full bg-purple-700 opacity-10 animate-blob3"></div>
        <div class="w-60 h-60 rounded-full bg-pink-700 opacity-10 animate-blob4"></div>
    </div>

    <!-- Footer Content -->
    <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8 relative z-10">
        <!-- Logo & Description -->
        <div>
        <h2 class="text-2xl font-bold mb-4 text-white">YourBrand</h2>
        <p class="mb-4">Creating innovative solutions for a better future. Stay connected with us!</p>
        <div class="flex space-x-4">
            <a href="#" class="hover:text-purple-400 transition duration-300">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <!-- Facebook Icon -->
                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.12 8.44 9.88v-6.99H12v-2.89h-1.56V9.41c0-1.54.92-2.39 2.34-2.39.68 0 1.39.12 1.39.12v1.53h-.78c-.77 0-1.01.48-1.01 1.02v1.27h2.73l-.44 2.89h-2.29V22C18.34 21.12 22 17 22 12z"/>
            </svg>
            </a>
            <a href="#" class="hover:text-purple-400 transition duration-300">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <!-- Twitter Icon -->
                <path d="M24 4.56c-.89.39-1.84.66-2.84.78 1.02-.61 1.8-1.58 2.17-2.73-.95.56-2.01.97-3.13 1.19-.9-.96-2.19-1.56-3.61-1.56-2.73 0-4.94 2.21-4.94 4.94 0 .39.04.77.12 1.14-4.1-.21-7.75-2.17-10.19-5.16-.43.74-.68 1.6-.68 2.52 0 1.74.89 3.28 2.24 4.17-.83-.03-1.61-.26-2.29-.63v.06c0 2.43 1.73 4.46 4.03 4.92-.42.11-.86.17-1.31.17-.32 0-.63-.03-.93-.09.63 1.97 2.45 3.41 4.6 3.45-1.68 1.32-3.81 2.11-6.11 2.11-.39 0-.78-.02-1.16-.07 2.19 1.4 4.8 2.22 7.61 2.22 9.13 0 14.13-7.56 14.13-14.13 0-.22-.01-.44-.02-.66.97-.7 1.8-1.58 2.46-2.58z"/>
            </svg>
            </a>
            <a href="#" class="hover:text-purple-400 transition duration-300">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <!-- Instagram Icon -->
                <path d="M12 2.16c3.18 0 3.58.01 4.85.07 1.17.05 1.98.24 2.43.41.58.22 1 .48 1.44.92.44.44.7.86.92 1.44.17.45.36 1.26.41 2.43.06 1.27.07 1.67.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.24 1.98-.41 2.43-.22.58-.48 1-.92 1.44-.44.44-.86.7-1.44.92-.45.17-1.26.36-2.43.41-1.27.06-1.67.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.98-.24-2.43-.41-.58-.22-1-.48-1.44-.92-.44-.44-.7-.86-.92-1.44-.17-.45-.36-1.26-.41-2.43-.06-1.27-.07-1.67-.07-4.85s.01-3.58.07-4.85c.05-1.17.24-1.98.41-2.43.22-.58.48-1 .92-1.44.44-.44.86-.7 1.44-.92.45-.17 1.26-.36 2.43-.41 1.27-.06 1.67-.07 4.85-.07zm0-2.16C8.75 0 8.33.01 7.06.07 5.79.13 4.78.33 3.98.66 3.24.96 2.61 1.33 2 1.95c-.72.66-1.38 1.38-1.95 2.09-.33.8-.53 1.81-.59 3.08C.01 8.33 0 8.75 0 12s.01 3.67.07 4.94c.06 1.27.26 2.28.59 3.08.57.71 1.23 1.43 1.95 2.09.66.66 1.38 1.38 2.09 1.95.8.33 1.81.53 3.08.59 1.27.06 1.69.07 4.94.07s3.67-.01 4.94-.07c1.27-.06 2.28-.26 3.08-.59.71-.57 1.43-1.23 2.09-1.95.66-.66 1.38-1.38 1.95-2.09.33-.8.53-1.81.59-3.08.06-1.27.07-1.69.07-4.94s-.01-3.67-.07-4.94c-.06-1.27-.26-2.28-.59-3.08-.57-.71-1.23-1.43-1.95-2.09-.66-.66-1.38-1.38-2.09-1.95-.8-.33-1.81-.53-3.08-.59C15.67.01 15.25 0 12 0zm0 5.84a6.16 6.16 0 100 12.32 6.16 6.16 0 000-12.32zm0 10.16a4 4 0 110-8 4 4 0 010 8zm6.4-11.84a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
            </svg>
            </a>
        </div>
        </div>
        <!-- Links -->
        <div>
        <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
        <ul class="space-y-2">
            <li><a href="#" class="hover:text-purple-400 transition">Home</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">About</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">Services</a></li>
            <li><a href="#" class="hover:text-purple-400 transition">Contact</a></li>
        </ul>
        </div>
        <!-- Newsletter Signup -->
        <div>
        <h3 class="text-xl font-semibold mb-4">Subscribe to our Newsletter</h3>
        <form class="flex flex-col sm:flex-row gap-2">
            <input type="email" placeholder="Your email" class="p-3 rounded-full bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-3 rounded-full font-semibold transition duration-300">
            Subscribe
            </button>
        </form>
        </div>
        <!-- Contact Info -->
        <div>
        <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
        <p class="mb-2">123 Main Street, City, Country</p>
        <p class="mb-2">Email: info@yourbrand.com</p>
        <p>Phone: +123 456 7890</p>
        </div>
    </div>
    <!-- Copyright -->
    <div class="mt-12 text-center text-gray-400 text-sm relative z-10">
        &copy; 2024 YourBrand. All rights reserved.
    </div>
    </footer>



    <script>
        (function() {
            // Feature detection for backdrop-filter
            const supportsBackdropFilter = CSS.supports('backdrop-filter', 'blur(12px)') || 
                                         CSS.supports('-webkit-backdrop-filter', 'blur(12px)');

            // Cache DOM elements
            const navbar = document.getElementById('navbar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeMobileMenu = document.getElementById('close-mobile-menu');
            let lastScroll = window.pageYOffset;

            // Initialize navbar state
            function initializeNavbar() {
                if (!supportsBackdropFilter) {
                    navbar.classList.add('bg-white-95');
                } else {
                    navbar.classList.add('bg-white-30', 'backdrop-blur-lg');
                }
                updateNavbarOnScroll();
            }

            // Update navbar on scroll
            function updateNavbarOnScroll() {
                const currentScroll = window.pageYOffset;
                if (currentScroll <= 10) {
                    navbar.classList.remove('bg-white-80', 'shadow-md');
                    if (supportsBackdropFilter) {
                        navbar.classList.add('bg-white-30', 'backdrop-blur-lg');
                    } else {
                        navbar.classList.add('bg-white-95');
                    }
                } else {
                    navbar.classList.remove('bg-white-30', 'backdrop-blur-lg');
                    navbar.classList.add('bg-white-80', 'shadow-md');
                }
                lastScroll = currentScroll;
            }

            // Toggle mobile menu
            function toggleMobileMenu() {
                mobileMenu.classList.toggle('hidden');
            }

            // Event listeners
            function setupEventListeners() {
                window.addEventListener('scroll', updateNavbarOnScroll, { passive: true });
                mobileMenuBtn?.addEventListener('click', toggleMobileMenu);
                closeMobileMenu?.addEventListener('click', toggleMobileMenu);

                // Persist navbar state on reload
                window.addEventListener('load', initializeNavbar);
            }

            // Utility classes
            const styleSheet = document.createElement('style');
            styleSheet.textContent = `
                .bg-white-30 { background: rgba(255, 255, 255, 0.3); }
                .bg-white-80 { background: rgba(255, 255, 255, 0.8); }
                .bg-white-95 { background: rgba(255, 255, 255, 0.95); }
                .backdrop-blur-lg { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
            `;
            document.head.appendChild(styleSheet);

            // Initialize
            setupEventListeners();
            initializeNavbar();
        })();
    </script>
</body>
</html>