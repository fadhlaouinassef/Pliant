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

    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Citoyen Color Variables */
        :root {
            --citoyen-primary: #1e40af;
            --citoyen-secondary: #3b82f6;
            --citoyen-accent: #93c5fd;
            --citoyen-dark: #1e3a8a;
            --citoyen-light: #dbeafe;
            --citoyen-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #2563eb 100%);
            --citoyen-glass: rgba(30, 64, 175, 0.1);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 16rem;
            min-height: 100vh;
            background: var(--citoyen-gradient);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(30, 64, 175, 0.2);
            color: white;
            position: fixed;
            z-index: 30;
            top: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 50%,
                rgba(255, 255, 255, 0.05) 100%
            );
            pointer-events: none;
        }
        
        .sidebar.collapsed {
            width: 4.5rem;
            box-shadow: 0 4px 20px rgba(30, 64, 175, 0.15);
        }
        
        /* Sidebar Header */
        .sidebar-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0 0 20px 20px;
            margin: 0 12px 24px 12px;
            padding: 20px 16px;
            transition: all 0.4s ease;
        }
        
        .sidebar.collapsed .sidebar-header {
            margin: 0 8px 20px 8px;
            padding: 16px 8px;
            border-radius: 0 0 12px 12px;
        }
        
        /* User Avatar Enhancements */
        .user-avatar {
            min-width: 2.5rem;
            position: relative;
            transition: all 0.4s ease;
        }
        
        .sidebar.collapsed .user-avatar {
            margin: 0 auto;
        }
        
        .user-avatar::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: var(--citoyen-gradient);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover::after {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .user-avatar img,
        .user-avatar div {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .user-avatar:hover img,
        .user-avatar:hover div {
            border-color: rgba(255, 255, 255, 0.6);
            transform: scale(1.05);
        }
        
        /* Sidebar Content Transitions */
        .sidebar-text {
            opacity: 1;
            width: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed .user-info {
            display: none;
        }
        
        /* Navigation Enhancements */
        .nav-item {
            margin: 0 12px 8px 12px;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar.collapsed .nav-item {
            margin: 0 8px 8px 8px;
            border-radius: 12px;
        }
        
        .nav-item a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 16px;
            backdrop-filter: blur(5px);
        }
        
        .sidebar.collapsed .nav-item a {
            padding: 12px 8px;
            justify-content: center;
            border-radius: 12px;
        }
        
        .nav-item a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: inherit;
        }
        
        .nav-item a:hover::before {
            opacity: 1;
        }
        
        .nav-item a:hover {
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar.collapsed .nav-item a:hover {
            transform: scale(1.05);
        }
        
        .nav-item.active a,
        .nav-item a.active-menu {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .nav-item.active a::after,
        .nav-item a.active-menu::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: white;
            border-radius: 0 2px 2px 0;
        }
        
        .nav-item svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed .nav-item svg {
            margin-right: 0;
        }
        
        /* Tooltips for Collapsed State */
        .sidebar.collapsed .nav-item {
            position: relative;
        }
        
        .sidebar.collapsed .nav-item::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin-left: 12px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar.collapsed .nav-item:hover::after {
            opacity: 1;
            visibility: visible;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px 20px 0 0;
            margin: 0 12px;
            transition: all 0.4s ease;
        }
        
        .sidebar.collapsed .sidebar-footer {
            margin: 0 8px;
            padding: 12px 8px;
            border-radius: 12px 12px 0 0;
        }
        
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .toggle-btn:hover::before {
            left: 100%;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        
        /* Main Content Container */
        .content-container {
            margin-left: 16rem;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .content-container.collapsed {
            margin-left: 4.5rem;
        }
        
        /* Main Content Area */
        .main-content {
            width: calc(100% - 16rem);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .main-content.collapsed {
            width: calc(100% - 4.5rem);
        }
        
        /* Navbar Styles */
        .navbar {
            left: 16rem;
            right: 0;
            top: 0;
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(30, 64, 175, 0.1);
            box-shadow: 0 2px 20px rgba(30, 64, 175, 0.1);
        }
        
        .navbar.collapsed {
            left: 4.5rem;
        }
        
        /* Custom Scrollbar for Notifications */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #2563eb, #1e40af);
        }
        
        /* Enhanced animations */
        @keyframes slideInDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slideInDown 0.2s ease-out;
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Mobile Styles */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 0 0 40px rgba(30, 64, 175, 0.3);
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
        
        /* Animation Enhancements */
        .nav-item a svg {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .nav-item a:hover svg {
            color: var(--citoyen-accent);
        }
        
        .nav-item.active a svg,
        .nav-item a.active-menu svg {
            color: white;
        }
        
        /* Toggle Icon Animation */
        .toggle-icon {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .toggle-icon-hidden {
            opacity: 0;
            transform: rotate(180deg) scale(0.8);
        }
        
        .toggle-icon-visible {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        
        /* Overlay for Mobile */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
            background: rgba(30, 64, 175, 0.4);
            backdrop-filter: blur(4px);
        }
        
        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/disable-auto-refresh.js') }}"></script>
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
            
            <!-- Sidebar Header -->
            <div class="sidebar-header flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <!-- User Avatar -->
                    <div class="user-avatar">
                        @if(Auth::user()->image)
                            <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                 alt="{{ Auth::user()->nom }}"
                                 class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- User Info -->
                    <div class="user-info sidebar-text">
                        <p class="font-bold text-white truncate">{{ Auth::user()->nom }}</p>
                        @if(Auth::user()->prenom)
                            <p class="text-sm text-blue-100 truncate opacity-90">{{ Auth::user()->prenom }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-2">
                <ul class="space-y-2">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="sidebar-text font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item" data-tooltip="Réclamations">
                        <a href="{{ route('citoyen.reclamations') }}" class="opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <span class="sidebar-text font-medium">Réclamations</span>
                        </a>
                    </li>
                    <li class="nav-item" data-tooltip="Interaction">
                        <a href="{{ route('citoyen.interactions') }}"
                         class="opacity-75">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 2a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="sidebar-text font-medium">Interaction</span>
                        </a>
                    </li>
                    <li class="nav-item" data-tooltip="Paramètres">
                        <a href="#" class="opacity-75">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="sidebar-text font-medium">Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <button @click="sidebarOpen = !sidebarOpen; Alpine.store('sidebar').toggle()" 
                        class="toggle-btn w-full flex items-center justify-center text-white hover:text-white"
                        data-tooltip="Réduire/Étendre">
                    <svg x-show="!sidebarOpen" 
                         class="h-5 w-5 toggle-icon" 
                         :class="{'toggle-icon-visible': !sidebarOpen, 'toggle-icon-hidden': sidebarOpen}"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                    <svg x-show="sidebarOpen" 
                         class="h-5 w-5 toggle-icon" 
                         :class="{'toggle-icon-visible': sidebarOpen, 'toggle-icon-hidden': !sidebarOpen}"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
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
                <div class="flex justify-between items-center px-6">
                    <div class="flex items-center">
                        <button @click="mobileSidebarOpen = true" 
                                class="md:hidden mr-4 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center space-x-4">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">@yield('title', 'Dashboard Citoyen')</h1>
                                <p class="text-sm text-gray-500">Gestion de vos réclamations</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="hidden lg:flex items-center bg-gray-50 rounded-xl px-4 py-2 w-80">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Rechercher..." class="bg-transparent border-none outline-none text-gray-700 placeholder-gray-400 w-full">
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                </svg>
                            </button>
                        </div>
                    
                        <!-- Notification Center -->
                        <div class="relative" x-data="notifications">
                            <button @click="notificationsOpen = !notificationsOpen" 
                                    class="relative p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="hasUnread" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                            </button>
                            
                            <!-- Notifications Dropdown Enhanced -->
                            <div x-show="notificationsOpen" 
                                 @click.away="notificationsOpen = false" 
                                 class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                                 x-cloak>
                                
                                <!-- Header -->
                                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-bold text-white text-lg">Notifications</h3>
                                        <button @click="markAllAsRead" 
                                                class="text-blue-100 hover:text-white text-sm font-medium px-3 py-1 rounded-lg hover:bg-white/20 transition-colors">
                                            Tout marquer
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Notifications List -->
                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    <template x-if="notifications.length === 0">
                                        <div class="p-8 text-center">
                                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">Aucune notification</p>
                                            <p class="text-gray-400 text-sm">Vous êtes à jour !</p>
                                        </div>
                                    </template>
                                    
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="navigateToReclamation(notification.reclamation_id, notification.id, notification.db_id)"
                                             :class="{'bg-blue-50 border-l-4 border-blue-500': !notification.etat}"
                                             class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-50 transition-colors">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                        <span x-text="notification.user_name.charAt(0).toUpperCase()"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 mb-1" x-text="notification.message || (notification.user_name + ' a ajouté un commentaire')"></p>
                                                    <p class="text-sm text-gray-600 mb-2 line-clamp-2" x-text="notification.commentaire"></p>
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs text-gray-500" x-text="notification.created_at"></span>
                                                        <span x-show="!notification.etat" class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Dropdown Enhanced -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200 border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    @if(Auth::user()->image)
                                        <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                             alt="{{ Auth::user()->nom }}"
                                             class="h-9 w-9 rounded-xl object-cover ring-2 ring-blue-500">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="hidden lg:block text-left">
                                        <p class="font-semibold text-gray-900 text-sm">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                                        <p class="text-xs text-gray-500">Citoyen</p>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 hidden lg:block" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 x-cloak>
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        @if(Auth::user()->image)
                                            <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                                 alt="{{ Auth::user()->nom }}"
                                                 class="h-12 w-12 rounded-xl object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                                            <p class="text-sm text-gray-500">Citoyen</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Mon Profile
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Paramètres
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-100 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
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

    <!-- Scripts Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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
            
            // Initialisation des notifications
            Alpine.data('notifications', () => ({
                notifications: [],
                hasUnread: false,
                notificationsOpen: false,
                
                init() {
                    // Charger les notifications depuis la base de données via l'API
                    this.loadDatabaseNotifications();
                    
                    // Initialiser Pusher avec debugging
                    const pusher = new Pusher('3c83f1ff7345a4689785', {
                        cluster: 'eu',
                        encrypted: true,
                        authEndpoint: '/broadcasting/auth',
                        auth: {
                            headers: {
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        }
                    });
                    
                    // Enable Pusher debugging
                    Pusher.logToConsole = true;
                    
                    // S'abonner au canal privé pour cet utilisateur
                    try {
                        // The channel name should be just 'reclamation.userId' without the 'private-' prefix
                        // Laravel Echo/Pusher automatically adds the 'private-' prefix when needed
                        const channelName = 'reclamation.{{ Auth::id() }}';
                        console.log('Subscribing to channel:', channelName);
                        
                        const channel = pusher.subscribe('private-' + channelName);
                        
                        // Connection status debugging
                        pusher.connection.bind('connected', () => {
                            console.log('Connected to Pusher!');
                            console.log('Connection state:', pusher.connection.state);
                        });
                        
                        pusher.connection.bind('error', (err) => {
                            console.error('Pusher connection error:', err);
                        });
                        
                        // Écouter l'événement 'new.comment'
                        channel.bind('new.comment', (data) => {
                            console.log('Received new.comment event with data:', data);
                            
                            // Vérifier si cette notification existe déjà dans notre liste
                            const exists = this.notifications.some(n => 
                                n.db_id && n.reclamation_id === data.reclamation_id && 
                                n.created_at === data.created_at
                            );
                            
                            if (!exists) {
                                // Ajouter un ID unique à la notification pour l'interface
                                const notification = {
                                    id: Date.now(),
                                    db_id: null, // Ce n'est pas une notification de la BD
                                    message: `${data.user_name} a commenté votre réclamation`,
                                    commentaire: data.commentaire,
                                    reclamation_id: data.reclamation_id,
                                    reclamation_titre: data.reclamation_titre,
                                    user_name: data.user_name,
                                    etat: false,
                                    created_at: data.created_at
                                };
                                
                                // Ajouter à la liste des notifications
                                this.notifications.unshift(notification);
                                
                                // Mettre à jour l'indicateur de notifications non lues
                                this.hasUnread = true;
                                
                                // Afficher une notification système si disponible
                                this.showSystemNotification(notification);
                            }
                        });
                        // Gestion des erreurs d'abonnement
                        channel.bind('pusher:subscription_error', (status) => {
                            console.error('Erreur d\'abonnement au canal', status);
                        });
                    } catch (error) {
                        console.error('Erreur lors de l\'initialisation de Pusher:', error);
                    }
                },
                
                // Charger les notifications depuis la base de données
                loadDatabaseNotifications() {
                    fetch('/notifications')
                        .then(response => response.json())
                        .then(data => {
                            // Transformer les notifications de la BD pour correspondre au format attendu
                            const notifications = data.map(notification => ({
                                id: Date.now() + Math.random(), // ID unique pour l'interface
                                db_id: notification.id, // ID de la BD pour les actions
                                message: notification.message,
                                commentaire: notification.data?.commentaire || '',
                                reclamation_id: notification.reclamation_id,
                                reclamation_titre: notification.data?.reclamation_titre || 'Réclamation',
                                user_name: notification.data?.user_name || '',
                                etat: notification.etat,
                                created_at: notification.created_at
                            }));
                            
                            this.notifications = notifications;
                            this.hasUnread = notifications.some(n => !n.etat);
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement des notifications:', error);
                        });
                },
                
                // Méthode pour naviguer vers la réclamation concernée
                navigateToReclamation(reclamationId, notificationId = null, dbId = null) {
                    // Si c'est une notification de la BD, la marquer comme lue
                    if (dbId) {
                        this.markAsRead(dbId);
                    }
                    
                    window.location.href = `/citoyen/reclamations?reclamation=${reclamationId}`;
                    this.notificationsOpen = false;
                    
                    // Pour les notifications en temps réel sans ID de BD, on les gère côté client
                    if (!dbId) {
                        // Marquer la notification comme lue
                        this.notifications.forEach(n => {
                            if (n.id === notificationId) {
                                n.etat = true;
                            }
                        });
                    }
                    
                    // Vérifier s'il reste des notifications non lues
                    this.hasUnread = this.notifications.some(n => !n.etat);
                },
                
                // Marquer une notification comme lue dans la base de données
                markAsRead(dbId) {
                    if (!dbId) return;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(`/notifications/${dbId}/mark-as-read`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mise à jour de l'état dans la liste locale
                            this.notifications.forEach(n => {
                                if (n.db_id === dbId) {
                                    n.etat = true;
                                }
                            });
                            
                            // Vérifier s'il reste des notifications non lues
                            this.hasUnread = this.notifications.some(n => !n.etat);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du marquage comme lu:', error);
                    });
                },
                
                // Marquer toutes les notifications comme lues
                markAllAsRead() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch('/notifications/mark-all-as-read', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mise à jour de l'état dans la liste locale
                            this.notifications.forEach(n => {
                                n.etat = true;
                            });
                            
                            this.hasUnread = false;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du marquage de toutes les notifications comme lues:', error);
                    });
                },
                
                // Méthode pour afficher une notification système
                showSystemNotification(data) {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        const notification = new Notification('Nouveau commentaire', {
                            body: `${data.user_name} a commenté sur votre réclamation "${data.reclamation_titre}"`,
                            icon: '/favicon.ico'
                        });
                        
                        notification.onclick = () => {
                            window.focus();
                            this.navigateToReclamation(data.reclamation_id, data.id, data.db_id);
                        };
                    } else if ('Notification' in window && Notification.permission !== 'denied') {
                        Notification.requestPermission();
                    }
                }
            }));
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