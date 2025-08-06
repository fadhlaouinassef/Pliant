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
        
        /* Admin Theme Variables */
        :root {
            --admin-primary: #0f172a;
            --admin-secondary: #1e293b;
            --admin-accent: #7c3aed;
            --admin-accent-light: #8b5cf6;
            --admin-accent-dark: #6d28d9;
            --admin-success: #059669;
            --admin-warning: #d97706;
            --admin-danger: #dc2626;
            --admin-info: #0284c7;
            --admin-light: #f8fafc;
            --admin-dark: #0f172a;
            --admin-border: #e2e8f0;
            --admin-text: #334155;
            --admin-text-muted: #64748b;
        }
        
        /* Background Patterns */
        .admin-bg-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(124, 58, 237, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(14, 165, 233, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, rgba(124, 58, 237, 0.02) 0%, rgba(14, 165, 233, 0.02) 100%);
        }
        
        /* Glassmorphism Effect */
        .admin-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 18rem;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
            color: white;
            position: fixed;
            z-index: 40;
            top: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1) 0%, rgba(14, 165, 233, 0.1) 100%);
            pointer-events: none;
        }
        
        .sidebar.collapsed {
            width: 5rem;
        }
        
        /* Sidebar Header */
        .sidebar-header {
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-dark) 100%);
            margin: 1rem;
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed .sidebar-header {
            margin: 0.5rem;
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }
        
        /* User Avatar Enhanced */
        .user-avatar {
            min-width: 3rem;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed .user-avatar {
            min-width: 2.5rem;
        }
        
        .user-avatar::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, var(--admin-accent), var(--admin-accent-light));
            border-radius: 50%;
            z-index: -1;
        }
        
        .user-avatar img, .user-avatar div {
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed .user-avatar img,
        .sidebar.collapsed .user-avatar div {
            width: 2.5rem !important;
            height: 2.5rem !important;
        }
        
        /* Sidebar Content Transitions */
        .sidebar-text {
            opacity: 1;
            width: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed .user-info {
            display: none;
        }
        
        /* Navigation Menu Enhanced */
        .nav-item {
            position: relative;
            margin: 0.25rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
            display: flex;
            justify-content: center;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-light) 100%);
            border-radius: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .nav-item:hover::before,
        .nav-item.active::before {
            opacity: 1;
        }
        
        .nav-item a {
            position: relative;
            z-index: 1;
            padding: 1rem;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .sidebar.collapsed .nav-item a {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .nav-item:hover a,
        .nav-item.active a {
            color: white;
            transform: translateX(2px);
        }
        
        .sidebar.collapsed .nav-item:hover a,
        .sidebar.collapsed .nav-item.active a {
            transform: scale(1.1);
        }
        
        .nav-item svg {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed .nav-item svg {
            margin-right: 0 !important;
        }
        
        .nav-item:hover svg,
        .nav-item.active svg {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
        }
        
        /* Tooltip for collapsed sidebar */
        .nav-item {
            position: relative;
        }
        
        .sidebar.collapsed .nav-item::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            margin-left: 0.5rem;
            z-index: 1000;
        }
        
        .sidebar.collapsed .nav-item:hover::after {
            opacity: 1;
        }
        
        /* Main Content Container */
        .content-container {
            margin-left: 18rem;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .content-container.collapsed {
            margin-left: 5rem;
        }
        
        /* Navbar Enhanced */
        .navbar {
            left: 18rem;
            right: 0;
            top: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--admin-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .navbar.collapsed {
            left: 5rem;
        }
        
        /* Main Content Area */
        .main-content {
            width: calc(100% - 18rem);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--admin-light);
        }
        
        .main-content.collapsed {
            width: calc(100% - 5rem);
        }
        
        /* Card Enhancements */
        .admin-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--admin-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .admin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--admin-accent) 0%, var(--admin-accent-light) 100%);
        }
        
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Button Enhancements */
        .admin-btn-primary {
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-light) 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .admin-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .admin-btn-primary:hover::before {
            left: 100%;
        }
        
        .admin-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
        }
        
        /* Notification Badge */
        .notification-badge {
            background: linear-gradient(135deg, var(--admin-danger) 0%, #ef4444 100%);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        /* Profile Dropdown Enhanced */
        .profile-dropdown {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--admin-border);
            overflow: hidden;
        }
        
        .profile-dropdown a {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .profile-dropdown a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--admin-accent);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .profile-dropdown a:hover::before {
            transform: scaleY(1);
        }
        
        .profile-dropdown a:hover {
            background: linear-gradient(90deg, rgba(124, 58, 237, 0.05) 0%, transparent 100%);
            color: var(--admin-accent);
        }
        
        /* Toggle Button Enhanced */
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        /* Mobile Styles */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
                width: 16rem;
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
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--admin-light);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-light) 100%);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--admin-accent-dark) 0%, var(--admin-accent) 100%);
        }
        
        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased admin-bg-pattern">
    <div class="min-h-screen" x-data="{ sidebarOpen: window.innerWidth > 768, mobileSidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" 
             @click="mobileSidebarOpen = false"
             class="sidebar-overlay fixed inset-0 z-30 bg-black bg-opacity-60 backdrop-blur-sm md:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar"
               :class="{'collapsed': !sidebarOpen && window.innerWidth > 768, 'open': mobileSidebarOpen}"
               x-show="sidebarOpen || mobileSidebarOpen || window.innerWidth > 768"
               x-transition:enter="transition ease-in-out duration-400"
               x-transition:enter-start="-translate-x-full md:translate-x-0"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-400"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full md:translate-x-0"
               x-cloak>
            
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="flex items-center space-x-4">
                    <!-- User Avatar -->
                    <div class="user-avatar">
                        @if(Auth::user()->image)
                            <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                 alt="{{ Auth::user()->nom }}"
                                 class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- User Info -->
                    <div class="user-info sidebar-text">
                        <p class="font-bold text-white text-lg truncate">{{ Auth::user()->nom }}</p>
                        @if(Auth::user()->prenom)
                            <p class="text-white text-opacity-80 text-sm truncate">{{ Auth::user()->prenom }}</p>
                        @endif
                        <div class="flex items-center mt-1">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-white text-opacity-70 text-xs">En ligne</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                        <a href="{{ route('admin.dashboard') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="sidebar-text font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}" data-tooltip="Utilisateurs">
                        <a href="{{ route('admin.utilisateurs.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <span class="sidebar-text font-medium">Utilisateurs</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.agents') ? 'active' : '' }}" data-tooltip="Agents">
                        <a href="{{ route('admin.agents') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span class="sidebar-text font-medium">Agents</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.avis') ? 'active' : '' }}" data-tooltip="Avis">
                        <a href="{{ route('admin.avis') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="sidebar-text font-medium">Avis</span>
                        </a>
                    </li>
                    <li class="nav-item" data-tooltip="Paramètres">
                        <a href="#" class="opacity-75">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <span class="sidebar-text font-medium">Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer -->
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="border-t border-white border-opacity-20 pt-4">
                    <button @click="sidebarOpen = !sidebarOpen; Alpine.store('sidebar').toggle()" 
                            class="toggle-btn w-full flex items-center justify-center text-white hover:text-white"
                            data-tooltip="Réduire/Étendre">
                        <svg x-show="!sidebarOpen" 
                             class="h-6 w-6 transition-all duration-300" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                        <svg x-show="sidebarOpen" 
                             class="h-6 w-6 transition-all duration-300" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="content-container"
             :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
            <!-- Enhanced Navbar -->
            <header class="navbar fixed admin-glass z-30 py-4"
                    :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
                <div class="flex justify-between items-center px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="mobileSidebarOpen = true" 
                                class="md:hidden p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-800 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                @yield('title', 'Tableau de bord Admin')
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">Gérez votre plateforme en toute simplicité</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Search Bar -->
                        <div class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 w-64">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Rechercher..." class="bg-transparent outline-none text-gray-700 placeholder-gray-500 w-full">
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="notification-badge absolute -top-1 -right-1 w-5 h-5 text-xs text-white rounded-full flex items-center justify-center">3</span>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-80 profile-dropdown py-2 z-50"
                                 x-cloak>
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">Nouvel utilisateur inscrit</p>
                                                <p class="text-xs text-gray-500">Il y a 5 minutes</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">Réclamation résolue</p>
                                                <p class="text-xs text-gray-500">Il y a 1 heure</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 p-2 rounded-full hover:bg-gray-100 transition-all duration-300 focus:outline-none">
                                @if(Auth::user()->image)
                                    <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                         alt="{{ Auth::user()->nom }}"
                                         class="h-10 w-10 rounded-full object-cover border-2 border-purple-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold border-2 border-purple-200">
                                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                                    <p class="text-xs text-gray-500">Administrateur</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-56 profile-dropdown py-2 z-50"
                                 x-cloak>
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-purple-600 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mon Profil
                                </a>
                                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:text-purple-600 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Paramètres
                                </a>
                                <div class="border-t border-gray-100 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content mt-20 p-6 min-h-screen"
                  :class="{'collapsed': !sidebarOpen && window.innerWidth > 768}">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/disable-auto-refresh.js') }}"></script>
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

        // Mobile menu close on link click
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('aside a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        document.querySelector('[x-data]').__x.$data.mobileSidebarOpen = false;
                    }
                });
            });
        });

        // Add loading states and smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading class to buttons on click
            document.querySelectorAll('button, a').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (this.classList.contains('admin-btn-primary')) {
                        this.style.pointerEvents = 'none';
                        setTimeout(() => {
                            this.style.pointerEvents = 'auto';
                        }, 1000);
                    }
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add ripple effect to buttons
            function createRipple(event) {
                const button = event.currentTarget;
                const circle = document.createElement('span');
                const diameter = Math.max(button.clientWidth, button.clientHeight);
                const radius = diameter / 2;

                circle.style.width = circle.style.height = `${diameter}px`;
                circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
                circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
                circle.classList.add('ripple');

                const ripple = button.getElementsByClassName('ripple')[0];
                if (ripple) {
                    ripple.remove();
                }

                button.appendChild(circle);
            }

            document.querySelectorAll('.admin-btn-primary').forEach(button => {
                button.addEventListener('click', createRipple);
            });
        });
    </script>
    
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 600ms linear;
            background-color: rgba(255, 255, 255, 0.6);
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>