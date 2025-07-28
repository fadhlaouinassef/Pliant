<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Sidebar Styles */
        .sidebar {
            width: 16rem;
            min-height: 100vh;
            background-color: #1f2937;
            color: white;
            position: fixed;
            z-index: 30;
            top: 0;
            transition: all 0.3s ease;
            transform: translateX(0);
        }
        
        .sidebar.collapsed {
            width: 4rem;
        }
        
        /* Sidebar Content Transitions */
        .sidebar-text {
            opacity: 1;
            width: auto;
            transition: opacity 0.3s ease 0.1s, width 0.3s ease 0.1s;
        }
        
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            transition: opacity 0.2s ease, width 0.3s ease;
        }
        
        /* User info styles */
        .user-avatar {
            min-width: 2.5rem;
        }
        
        .user-avatar img {
            border: 2px solid #4f46e5;
        }
        
        .sidebar.collapsed .user-info {
            display: none;
        }
        
        /* Main Content Container */
        .content-container {
            margin-left: 16rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .content-container.collapsed {
            margin-left: 4rem;
        }
        
        /* Main Content Area */
        .main-content {
            width: calc(100% - 16rem);
            transition: width 0.3s ease;
        }
        
        .main-content.collapsed {
            width: calc(100% - 4rem);
        }
        
        /* Navbar Styles */
        .navbar {
            left: 16rem;
            right: 0;
            top: 0;
            transition: left 0.3s ease;
        }
        
        .navbar.collapsed {
            left: 4rem;
        }
        
        /* Mobile Styles */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content-container,
            .content-container.collapsed {
                margin-left: 0 !important;
            }
            
            .main-content,
            .main-content.collapsed {
                width: 100% !important;
            }
            
            .navbar,
            .navbar.collapsed {
                left: 0 !important;
            }
        }
        
        /* Active Menu Item */
        .active-menu {
            background-color: #4f46e5 !important;
            color: white !important;
        }
        
        .active-menu svg {
            color: white !important;
        }
        
        /* Toggle Button Animation */
        .toggle-icon {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .toggle-icon-hidden {
            opacity: 0;
            transform: translateX(-10px);
        }
        
        .toggle-icon-visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Overlay for Mobile */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen" x-data="{ sidebarOpen: window.innerWidth > 768, mobileSidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" 
             @click="mobileSidebarOpen = false"
             class="sidebar-overlay fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar py-4"
               :class="{'collapsed': !sidebarOpen && window.innerWidth > 768, 'open': mobileSidebarOpen}"
               x-show="sidebarOpen || mobileSidebarOpen || window.innerWidth > 768"
               x-transition:enter="transition ease-in-out duration-300"
               x-transition:enter-start="-translate-x-full md:translate-x-0"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full md:translate-x-0"
               x-cloak>
            <div class="px-4 mt-4 mb-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <!-- User Avatar -->
                    <div class="user-avatar">
                        @if(Auth::user()->image)
                            <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                 alt="{{ Auth::user()->nom }}"
                                 class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- User Info -->
                    <div class="user-info sidebar-text">
                        <p class="font-bold truncate">{{ Auth::user()->nom }}</p>
                        @if(Auth::user()->prenom)
                            <p class="font-bold text-sm text-gray-300 truncate">{{ Auth::user()->prenom }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <nav class="mt-10">
                <ul>
                    <li class="mb-1">
                        <a href="{{ route('admin.dashboard') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.utilisateurs.index') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('admin.utilisateurs.index') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Utilisateurs</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.agents') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('admin.agents') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 sidebar-text">Agents</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('admin.avis') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('admin.avis') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Avis</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 sidebar-text">Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t border-indigo-700">
                <button @click="sidebarOpen = !sidebarOpen; Alpine.store('sidebar').toggle()" 
                        class="flex items-center justify-center w-full text-white hover:text-gray-200 relative">
                    <svg x-show="!sidebarOpen" 
                         class="h-6 w-6 toggle-icon absolute" 
                         :class="{'toggle-icon-visible': !sidebarOpen, 'toggle-icon-hidden': sidebarOpen}"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <svg x-show="sidebarOpen" 
                         class="h-6 w-6 toggle-icon absolute" 
                         :class="{'toggle-icon-visible': sidebarOpen, 'toggle-icon-hidden': !sidebarOpen}"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="content-container"
             :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
            <!-- Navbar -->
            <header class="navbar fixed bg-white shadow-sm top-0 z-20 py-3"
                    :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
                <div class="flex justify-between items-center px-4 sm:px-6">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" 
                                class="md:hidden mr-3 text-gray-500 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-lg font-semibold text-gray-900">@yield('title', 'Tableau de bord Admin')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notification Icon -->
                        <button class="p-1 text-gray-500 hover:text-gray-700 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 focus:outline-none">
                                @if(Auth::user()->image)
                                    <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                         alt="{{ Auth::user()->nom }}"
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden md:inline text-gray-700">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                 x-cloak>
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Déconnexion') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content mt-16 p-4 sm:p-6 bg-gray-50 text-gray-900"
                  :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth > 768,
                toggle() {
                    this.open = !this.open;
                    localStorage.setItem('sidebarOpen', this.open);
                }
            });
        });

        document.querySelectorAll('aside a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    Alpine.store('sidebar').mobileSidebarOpen = false;
                }
            });
        });
    </script>
</body>
</html>