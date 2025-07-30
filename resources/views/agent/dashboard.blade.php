<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Agent</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script sr                // Afficher une notification système
                showSystemNotification(data) {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        const notification = new Notification('Nouveau commentaire', {
                            body: `${data.user_name} a commenté une réclamation que vous traitez`,
                            icon: '/favicon.ico'
                        });
                        
                        notification.onclick = () => {
                            window.focus();
                            this.navigateToReclamation(data.reclamation_id, data.id, data.db_id);
                        };
                    } else if ('Notification' in window && Notification.permission !== 'denied') {
                        Notification.requestPermission();
                    }
                }sdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
                        <a href="{{ route('agent.dashboard') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('agent.dashboard') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('agent.coéquipiers') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('agent.coéquipiers') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            <span class="ml-3 sidebar-text">Coéquipiers</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('agent.reclamations') }}" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors {{ request()->routeIs('agent.reclamations') ? 'active-menu' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 sidebar-text">Réclamations</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="#" 
                        class="flex items-center px-4 py-3 hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 2a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 sidebar-text">Interaction</span>
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
                        <h2 class="text-lg font-semibold text-gray-900">@yield('title', 'Tableau de bord Citoyen')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notification Icon -->
                        <div class="relative" x-data="notifications">
                            <button @click="notificationsOpen = !notificationsOpen" 
                                    class="p-1 text-gray-500 hover:text-gray-700 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="hasUnread" class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Dropdown de notifications -->
                            <div x-show="notificationsOpen" 
                                 @click.away="notificationsOpen = false" 
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50"
                                 x-cloak>
                                <div class="px-4 py-2 flex justify-between items-center border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                                    <button 
                                        @click="markAllAsRead"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Tout marquer comme lu
                                    </button>
                                </div>
                                
                                <div x-show="notifications.length === 0" class="px-4 py-3 text-sm text-gray-500">
                                    Aucune notification
                                </div>
                                
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="navigateToReclamation(notification.reclamation_id, notification.id, notification.db_id)"
                                             :class="{'bg-blue-50': !notification.etat}"
                                             class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                                        <span x-text="notification.user_name.charAt(0)"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900" x-text="notification.message || (notification.user_name + ' a commenté une réclamation')"></p>
                                                    <p class="text-sm text-gray-600 mt-1" x-text="notification.commentaire"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-text="notification.created_at"></p>
                                                </div>
                                                <div x-show="!notification.etat" class="ml-2 flex-shrink-0">
                                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
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
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth > 768,
                toggle() {
                    this.open = !this.open;
                    localStorage.setItem('sidebarOpen', this.open);
                }
            });
            
            // Initialisation des notifications pour l'agent
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
                    
                    // S'abonner au canal privé pour cet agent
                    try {
                        // The channel name should be 'agent-reclamation.userId'
                        const channelName = 'agent-reclamation.{{ Auth::id() }}';
                        console.log('Agent subscribing to channel:', channelName);
                        
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
                            console.log('Agent received new.comment event with data:', data);
                            
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
                                    message: `${data.user_name} a commenté une réclamation que vous traitez`,
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
                    
                    window.location.href = `/agent/reclamations?reclamation=${reclamationId}`;
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
                
                // Afficher une notification système
                showSystemNotification(data) {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        const notification = new Notification('Nouveau commentaire sur votre réclamation', {
                            body: `${data.user_name} a commenté: ${data.commentaire}`,
                            icon: '/images/logo.png'
                        });
                        
                        notification.onclick = () => {
                            window.focus();
                            this.navigateToReclamation(data.reclamation_id);
                        };
                    } else if ('Notification' in window && Notification.permission !== 'denied') {
                        Notification.requestPermission();
                    }
                }
            }));
        });
    </script>
</body>
</html>