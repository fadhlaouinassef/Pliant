@extends('admin.dashboard')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Animation pour le chargement des graphiques */
    @keyframes chartFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .chart-container {
        animation: chartFadeIn 0.6s ease-out forwards;
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    /* Style pour les étiquettes de données */
    .data-label {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        font-size: 0.875rem;
    }
    .data-label-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    /* Hover effects */
    .hover-scale {
        transition: transform 0.2s;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }
    
    /* Style pour les statistiques */
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .stat-card-primary {
        border-left-color: #4f46e5;
    }
    .stat-card-success {
        border-left-color: #10b981;
    }
    .stat-card-warning {
        border-left-color: #f59e0b;
    }
    .stat-card-danger {
        border-left-color: #ef4444;
    }
    
    /* Styles responsifs pour les tableaux */
    .w-full.overflow-x-auto {
        max-width: 100%;
        position: relative;
        z-index: 10; /* Assurer que le contenu est au-dessus des éléments potentiellement en chevauchement */
    }
    
    /* Style pour améliorer la cliquabilité des boutons */
    button {
        position: relative;
        z-index: 20; /* Assurer que les boutons sont toujours cliquables */
    }
    
    /* Éviter que le contenu ne déborde quand la navbar est ouverte */
    #content-wrapper {
        min-width: 0;
        width: 100%;
        overflow-x: hidden;
    }
    
    [x-cloak] { 
        display: none !important; 
    }
</style>
@endsection

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div x-data="userCrud()" class="w-full mx-auto px-4 py-8">
    <!-- En-tête de la page avec statistiques -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold mb-2">Gestion des Utilisateurs</h1>
                <p class="text-indigo-100">Consultez et gérez tous les utilisateurs de la plateforme</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $utilisateurs->total() }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Total des utilisateurs</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $utilisateurs->where('role', 'admin')->count() }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Administrateurs</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $utilisateurs->where('role', 'agent')->count() }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Agents</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-primary hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Utilisateurs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $utilisateurs->total() }}</h3>
                    <p class="text-sm text-indigo-600 mt-2">{{ number_format($utilisateurs->where('created_at', '>=', now()->subMonth())->count() / max(1, $utilisateurs->total()) * 100, 1) }}% nouveaux ce mois</p>
                </div>
                <div class="rounded-full p-3 bg-indigo-100 text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-success hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Citoyens</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $utilisateurs->where('role', 'citoyen')->count() }}</h3>
                    <p class="text-sm text-green-600 mt-2">{{ number_format($utilisateurs->where('role', 'citoyen')->count() / max(1, $utilisateurs->total()) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-warning hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Agents</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $utilisateurs->where('role', 'agent')->count() }}</h3>
                    <p class="text-sm text-yellow-600 mt-2">{{ number_format($utilisateurs->where('role', 'agent')->count() / max(1, $utilisateurs->total()) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-danger hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Administrateurs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $utilisateurs->where('role', 'admin')->count() }}</h3>
                    <p class="text-sm text-red-600 mt-2">{{ number_format($utilisateurs->where('role', 'admin')->count() / max(1, $utilisateurs->total()) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-red-100 text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Graphique par rôle -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribution par Rôle</h2>
            <div class="h-64">
                <canvas id="roleChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #10B981"></span>
                    <span>Administrateurs</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #FBBF24"></span>
                    <span>Agents</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #3B82F6"></span>
                    <span>Citoyens</span>
                </div>
            </div>
        </div>
        
        <!-- Graphique par date de création (groupé par mois) -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Utilisateurs par Mois de Création</h2>
            <div class="h-64">
                <canvas id="creationDateChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #3B82F6"></span>
                    <span>Nombre d'utilisateurs</span>
                </div>
            </div>
        </div>
        
        <!-- Graphique par initiale email -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribution par Initiale Email</h2>
            <div class="h-64">
                <canvas id="emailInitialChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #10B981"></span>
                    <span>Nombre d'utilisateurs</span>
                </div>
            </div>
        </div>
        
        <!-- Graphique par domaine email -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribution par Domaine Email</h2>
            <div class="h-64">
                <canvas id="emailDomainChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: #10B981"></span>
                    <span>Domaines</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des utilisateurs -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des Utilisateurs</h2>
            <div class="flex space-x-2">
                <button @click="filterUsers('tous')" :class="{'bg-indigo-600 text-white': userFilter === 'tous', 'bg-gray-200 text-gray-700': userFilter !== 'tous'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Tous
                </button>
                <button @click="filterUsers('citoyen')" :class="{'bg-indigo-600 text-white': userFilter === 'citoyen', 'bg-gray-200 text-gray-700': userFilter !== 'citoyen'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Citoyens
                </button>
                <button @click="filterUsers('agent')" :class="{'bg-indigo-600 text-white': userFilter === 'agent', 'bg-gray-200 text-gray-700': userFilter !== 'agent'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Agents
                </button>
                <button @click="filterUsers('admin')" :class="{'bg-indigo-600 text-white': userFilter === 'admin', 'bg-gray-200 text-gray-700': userFilter !== 'admin'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Administrateurs
                </button>
                <button @click="openAddModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter
                </button>
            </div>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-sm uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Photo</th>
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Prénom</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Téléphone & Adresse</th>
                        <th class="px-6 py-3 text-left">Date de création</th>
                        <th class="px-6 py-3 text-left">Rôle</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="filteredUsers.length === 0">
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    </template>
                    <template x-for="(user, index) in filteredUsers" :key="user.id">
                        <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'" class="hover:bg-indigo-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="user.image">
                                    <img :src="'/images/' + user.image" alt="Photo profil" class="h-10 w-10 rounded-full object-cover border-2 border-indigo-200">
                                </template>
                                <template x-if="!user.image">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                        <span x-text="user.nom.charAt(0).toUpperCase()"></span>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800" x-text="user.nom"></td>
                            <td class="px-6 py-4 whitespace-nowrap" x-text="user.prenom"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span x-text="user.email"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span x-text="user.num_tlph"></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span x-text="user.adresse ? user.adresse : 'Non renseignée'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" x-text="formatDate(user.created_at)"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'bg-green-100 text-green-800': user.role === 'admin',
                                    'bg-yellow-100 text-yellow-800': user.role === 'agent',
                                    'bg-blue-100 text-blue-800': user.role === 'citoyen'
                                }" class="px-2 py-1 rounded-full text-xs font-medium">
                                    <span x-text="user.role"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="openEditModal(user.id)" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-full hover:bg-indigo-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(user.id)" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $utilisateurs->links() }}
        </div>
    </div>

    <!-- Modal d'ajout ou d'édition -->
    <div x-show="isModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="isModalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="isModalOpen" @click.away="isModalOpen = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
                    <form id="userForm" :action="formAction" method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-if="currentUserId">
                            @method('PUT')
                        </template>
                        <input type="hidden" x-model="currentUserId" name="id">
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="nom" id="nom" x-model="currentUser.nom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-3">
                                <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" name="prenom" id="prenom" x-model="currentUser.prenom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-6">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" x-model="currentUser.email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-3">
                                <label for="num_tlph" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                <input type="text" name="num_tlph" id="num_tlph" x-model="currentUser.num_tlph" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-3">
                                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                <select id="role" name="role" x-model="currentUser.role" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="citoyen">Citoyen</option>
                                    <option value="agent">Agent</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                            </div>
                            <div class="sm:col-span-6">
                                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                                <input type="text" name="adresse" id="adresse" x-model="currentUser.adresse" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-6" x-show="!currentUserId">
                                <label for="mdp" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <input type="password" name="mdp" id="mdp" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="sm:col-span-6">
                                <label for="image" class="block text-sm font-medium text-gray-700">Photo de profil</label>
                                <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="submitForm()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Enregistrer
                    </button>
                    <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div x-show="isDeleteModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="isDeleteModalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="isDeleteModalOpen" @click.away="isDeleteModalOpen = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmer la suppression</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" :action="deleteAction" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="executeDelete()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                    </form>
                    <button type="button" @click="isDeleteModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         @click="showToast = false"
         class="fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg" 
         :class="toastType === 'success' ? 'bg-green-500 text-white' : (toastType === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white')"
         x-cloak>
        <div class="flex items-center space-x-2">
            <template x-if="toastType === 'success'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </template>
            <template x-if="toastType === 'error'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </template>
            <template x-if="toastType === 'info'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </template>
            <span x-text="toastMessage"></span>
        </div>
    </div>

    <!-- Indicateur de chargement -->
    <div x-show="isLoading" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
                <p class="text-gray-700 text-lg">Chargement en cours...</p>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    [x-cloak] { 
        display: none !important; 
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données des utilisateurs
    const users = @json($utilisateurs->items());
    console.log('Users data:', users);

    // 1. Graphique par rôle
    const roleCounts = users.reduce((acc, user) => {
        acc[user.role] = (acc[user.role] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('roleChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(roleCounts),
            datasets: [{
                data: Object.values(roleCounts),
                backgroundColor: ['#10B981', '#FBBF24', '#3B82F6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // 2. Graphique par date de création (groupé par mois)
    const monthCounts = users.reduce((acc, user) => {
        const date = new Date(user.created_at);
        const monthYear = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        acc[monthYear] = (acc[monthYear] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('creationDateChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthCounts),
            datasets: [{
                label: 'Nombre d\'utilisateurs',
                data: Object.values(monthCounts),
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 3. Graphique par initiale email (remplace longueur du numéro)
    const emailInitialCounts = users.reduce((acc, user) => {
        const initial = user.email.charAt(0).toUpperCase();
        acc[initial] = (acc[initial] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('emailInitialChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(emailInitialCounts),
            datasets: [{
                label: 'Nombre d\'utilisateurs',
                data: Object.values(emailInitialCounts),
                backgroundColor: '#10B981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 4. Graphique par domaine email
    const domainCounts = users.reduce((acc, user) => {
        const domain = user.email.split('@')[1];
        acc[domain] = (acc[domain] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('emailDomainChart'), {
        type: 'polarArea',
        data: {
            labels: Object.keys(domainCounts),
            datasets: [{
                data: Object.values(domainCounts),
                backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EC4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});

// Code Alpine.js
function userCrud() {
    return {
        isModalOpen: false,
        isDeleteModalOpen: false,
        isLoading: false,
        showToast: false,
        toastMessage: '',
        toastType: 'info', // 'success', 'error', 'info'
        modalTitle: '',
        formAction: '',
        deleteAction: '',
        currentUserId: null,
        userFilter: 'tous',
        filteredUsers: [],
        currentUser: {
            nom: '',
            prenom: '',
            email: '',
            num_tlph: '',
            adresse: '',
            role: 'citoyen',
            image: ''
        },
        
        init() {
            // Écouter les messages flash
            @if(session('success'))
                this.showToastMessage('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToastMessage('{{ session('error') }}', 'error');
            @endif

            // Initialiser les utilisateurs filtrés
            this.filteredUsers = @json($utilisateurs->items());
        },
        
        filterUsers(role) {
            this.userFilter = role;
            const allUsers = @json($utilisateurs->items());
            
            if (role === 'tous') {
                this.filteredUsers = allUsers;
            } else {
                this.filteredUsers = allUsers.filter(user => user.role === role);
            }
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('fr-FR', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        },
        
        openAddModal() {
            this.modalTitle = 'Ajouter un utilisateur';
            this.formAction = '{{ route("admin.utilisateurs.store") }}';
            this.currentUserId = null;
            this.resetForm();
            this.isModalOpen = true;
        },
        
        openEditModal(userId) {
            this.modalTitle = 'Modifier l\'utilisateur';
            this.formAction = '{{ route("admin.utilisateurs.update", ":id") }}'.replace(':id', userId);
            this.currentUserId = userId;
            
            const user = @json($utilisateurs->items()).find(u => u.id === userId);
            if (user) {
                this.currentUser = {
                    nom: user.nom,
                    prenom: user.prenom,
                    email: user.email,
                    num_tlph: user.num_tlph,
                    adresse: user.adresse,
                    role: user.role,
                    image: user.image
                };
            }
            
            this.isModalOpen = true;
        },
        
        confirmDelete(userId) {
            this.deleteAction = '{{ route("admin.utilisateurs.destroy", ":id") }}'.replace(':id', userId);
            this.isDeleteModalOpen = true;
        },
        
        showToastMessage(message, type = 'info') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },
        
        submitForm() {
            this.isLoading = true;
            this.showToastMessage('Traitement en cours...', 'info');
            
            // Soumettre le formulaire après un court délai pour que le toast s'affiche
            setTimeout(() => {
                document.getElementById('userForm').submit();
            }, 500);
        },
        
        executeDelete() {
            this.isLoading = true;
            this.showToastMessage('Suppression en cours...', 'info');
            
            // Soumettre le formulaire après un court délai pour que le toast s'affiche
            setTimeout(() => {
                document.getElementById('deleteForm').submit();
            }, 500);
        },
        
        resetForm() {
            this.currentUser = {
                nom: '',
                prenom: '',
                email: '',
                num_tlph: '',
                adresse: '',
                role: 'citoyen',
                image: ''
            };
        }
    }
}
</script>
@endsection