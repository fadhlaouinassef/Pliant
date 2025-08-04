@extends('agent.dashboard')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom Styles for Filtering and Animations -->
<style>
    .reclamation-card {
        transition: all 0.3s ease-in-out;
        transform: scale(1);
    }
    
    .reclamation-card:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .filter-animation {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .export-loading {
        position: relative;
        overflow: hidden;
    }
    
    .export-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    /* Status filter highlight */
    #statusFilter:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* No results message animation */
    #no-results-message {
        animation: slideUp 0.4s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div x-data="reclamationManager()" class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Réclamations</h1>
                <p class="text-gray-600 mt-2">Tableau de bord et statistiques de vos réclamations assignées</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Dernière mise à jour</p>
                    <p class="text-lg font-semibold text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @php
        $totalReclamations = $reclamations->count();
        $enAttente = $reclamations->where('status', 'en attente')->count();
        $enCours = $reclamations->where('status', 'en cours')->count();
        $resolues = $reclamations->where('status', 'résolue')->count();
        $rejetees = $reclamations->where('status', 'rejetée')->count();
        $prioriteElevee = $reclamations->where('priorite', 'élevée')->count();
        $avgResolutionTime = 3.2; // Exemple - à calculer selon votre logique
        $satisfactionRate = 87; // Exemple - à calculer selon votre logique
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Réclamations -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Réclamations</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $totalReclamations }}</p>
                    <p class="text-blue-600 text-sm mt-1">Assignées à vous</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Attente -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">En Attente</p>
                    <p class="text-3xl font-bold text-yellow-900">{{ $enAttente }}</p>
                    <p class="text-yellow-600 text-sm mt-1">{{ $totalReclamations > 0 ? round(($enAttente / $totalReclamations) * 100, 1) : 0 }}% du total</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- En Cours -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-600 text-sm font-medium">En Cours</p>
                    <p class="text-3xl font-bold text-indigo-900">{{ $enCours }}</p>
                    <p class="text-indigo-600 text-sm mt-1">{{ $totalReclamations > 0 ? round(($enCours / $totalReclamations) * 100, 1) : 0 }}% du total</p>
                </div>
                <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Résolues -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Résolues</p>
                    <p class="text-3xl font-bold text-green-900">{{ $resolues }}</p>
                    <p class="text-green-600 text-sm mt-1">{{ $totalReclamations > 0 ? round(($resolues / $totalReclamations) * 100, 1) : 0 }}% du total</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition par Statut</h3>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Priority Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition par Priorité</h3>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Timeline Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Évolution des Réclamations (7 derniers jours)</h3>
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
        <div class="relative h-80">
            <canvas id="timelineChart"></canvas>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Temps Moyen de Résolution -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Temps Moyen de Résolution</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $avgResolutionTime }}j</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <p class="text-green-600 text-sm">-0.8j ce mois</p>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taux de Satisfaction -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Taux de Satisfaction</p>
                    <p class="text-3xl font-bold text-purple-900">{{ $satisfactionRate }}%</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <p class="text-green-600 text-sm">+5% ce mois</p>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Priorité Élevée -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Priorité Élevée</p>
                    <p class="text-3xl font-bold text-red-900">{{ $prioriteElevee }}</p>
                    <p class="text-red-600 text-sm mt-1">Nécessitent attention</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-colors">
                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                </svg>
                <span class="text-blue-700 font-medium">Traiter les réclamations urgentes</span>
            </button>
            
            <button class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-700 font-medium">Marquer comme résolues</span>
            </button>
            
            <button class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition-colors">
                <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="text-purple-700 font-medium">Générer rapport</span>
            </button>
        </div>
    </div>

    <!-- Réclamations List Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-900">Liste des Réclamations</h3>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Filtrer par:</label>
                    <select id="statusFilter" x-model="statusFilter" @change="filterReclamations()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Toutes</option>
                        <option value="en attente">En attente</option>
                        <option value="en cours">En cours</option>
                        <option value="résolue">Résolues</option>
                        <option value="rejetée">Rejetées</option>
                    </select>
                </div>
                <button @click="exportToPDF()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Overlay for Blur -->
    <div x-show="showOverlay" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-40" @click="closeAllPopups()"></div>
    
    
    <!-- Popup for viewing reclamation details -->
    <div x-show="showDetailsPopup" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-2xl" x-cloak>
        <div class="bg-white rounded-lg shadow-xl border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Détails de la réclamation</h3>
                    <div class="flex space-x-2">
                        <button x-show="statusChanged" @click="saveStatus()" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Enregistrer
                        </button>
                        <button @click="closeAllPopups()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-2" x-text="selectedReclamation.titre"></h4>
                    <p class="text-gray-700" x-text="selectedReclamation.description"></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Informations</h5>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-text="'Créée le: ' + selectedReclamation.createdAt"></span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span x-text="'Priorité: ' + selectedReclamation.priorite"></span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14 a7 7 0 00-7-7z"></path>
                                </svg>
                                <span x-text="'Citoyen ID: ' + selectedReclamation.citoyen"></span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Statut</h5>
                        <div class="flex items-center mb-3">
                            <form id="status-form" :action="'/reclamations/' + selectedReclamation.id + '/status'" method="POST">
                                @csrf
                                @method('PATCH')
                                <select 
                                    name="status" 
                                    x-model="currentStatus" 
                                    @change="statusChanged = currentStatus !== selectedReclamation.originalStatus"
                                    class="px-3 py-1 text-xs font-medium rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    :class="selectedReclamation.statusClass"
                                >
                                    <option value="en attente">En attente</option>
                                    <option value="en cours">En cours</option>
                                    <option value="résolue">Résolue</option>
                                    <option value="rejetée">Rejetée</option>
                                </select>
                            </form>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p x-text="'Dernière mise à jour: ' + selectedReclamation.updatedAt"></p>
                            <p>Progression: En attente de traitement</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h5 class="font-medium text-gray-900 mb-2">Commentaires</h5>
                    <div class="text-sm text-gray-700 space-y-3" id="comments-section">
                        <template x-if="comments.length === 0 && !commentsLoading">
                            <p class="text-gray-500">Aucun commentaire pour cette réclamation.</p>
                        </template>
                        <template x-if="commentsLoading">
                            <p class="text-gray-500">Chargement des commentaires...</p>
                        </template>
                        <template x-for="comment in displayComments" :key="comment.id">
                            <div class="border-b border-gray-200 pb-3 mb-3">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-medium text-gray-900" x-text="comment.id_ecrivain"></span>
                                    <span class="text-xs text-gray-500" x-text="comment.created_at"></span>
                                </div>
                                <p class="text-gray-700 mb-2" x-text="comment.commentaire"></p>
                                <template x-if="comment.can_delete">
                                    <div class="flex justify-end">
                                        <button 
                                            @click="confirmDeleteComment(comment.id)" 
                                            class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center"
                                        >
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    <button 
                        x-show="comments.length > 2"
                        @click="isCommentsExpanded = !isCommentsExpanded; updateDisplayComments()"
                        class="mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        <span x-text="isCommentsExpanded ? 'Voir moins' : 'Voir plus'"></span>
                    </button>
                    
                    <!-- Comment form -->
                    <div x-show="showCommentForm" class="mt-4">
                        <form @submit.prevent="handleCommentSubmit" action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_reclamation" x-model="selectedReclamation.id">
                            <div class="mb-4">
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Votre commentaire</label>
                                <textarea 
                                    name="commentaire" 
                                    id="commentaire" 
                                    x-model="commentText"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                                    rows="4"
                                    placeholder="Saisir votre commentaire ici..."
                                    required
                                ></textarea>
                                @error('commentaire')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                <!-- Error message for AJAX errors -->
                                <div x-show="commentError" x-text="commentError" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="showCommentForm = false"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    :disabled="commentSubmitting"
                                    class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <span x-show="!commentSubmitting">Envoyer</span>
                                    <span x-show="commentSubmitting">Envoi en cours...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <button 
                        @click="closeAllPopups()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Fermer
                    </button>
                    <button
                        @click="showCommentForm = true"
                        class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Ajouter un commentaire
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($reclamations) && $reclamations->count() > 0)
        <div id="reclamations-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reclamations as $reclamation)
                <div class="reclamation-card bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                    @if($reclamation->status == 'résolue') border-green-500
                    @elseif($reclamation->status == 'rejetée') border-red-500
                    @elseif($reclamation->status == 'en cours') border-indigo-500
                    @else border-yellow-500 @endif"
                    data-status="{{ $reclamation->status }}"
                    data-priorite="{{ $reclamation->priorite }}"
                    data-date="{{ $reclamation->created_at->format('Y-m-d') }}"
                    data-titre="{{ $reclamation->titre }}"
                    data-description="{{ $reclamation->description }}"
                    data-citoyen="{{ $reclamation->nom_citoyen ?? 'Non spécifié' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $reclamation->titre }}</h2>
                            <span class="px-3 py-1 
                                @if($reclamation->status == 'résolue') bg-green-100 text-green-800
                                @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800
                                @elseif($reclamation->status == 'en cours') bg-indigo-100 text-indigo-800
                                @else bg-yellow-100 text-yellow-800 @endif
                                text-xs font-medium rounded-full flex items-center">
                                @if($reclamation->status == 'résolue')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($reclamation->status == 'rejetée')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                @elseif($reclamation->status == 'en cours')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                                {{ ucfirst($reclamation->status) }}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($reclamation->description, 150) }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Créée le: {{ $reclamation->created_at->format('d/m/Y') }}</span>
                            </div>
                            
                            <div class="flex items-center">
                                @if($reclamation->priorite == 'faible')
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    <span class="text-green-600">Priorité: Faible</span>
                                @elseif($reclamation->priorite == 'moyenne')
                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                    </svg>
                                    <span class="text-yellow-600">Priorité: Moyenne</span>
                                @else
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span class="text-red-600">Priorité: Élevée</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14 a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Citoyen: {{ $reclamation->nom_citoyen ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 px-6 py-4 flex justify-end">
                        <button 
                            @click="openReclamation($event, {{ $reclamation->id }}, '{{ $reclamation->titre }}', `{{ $reclamation->description }}`, '{{ $reclamation->created_at->format('d/m/Y') }}', '{{ ucfirst($reclamation->priorite) }}', '{{ $reclamation->status }}', '@if($reclamation->status == 'résolue') bg-green-100 text-green-800 @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800 @elseif($reclamation->status == 'en cours') bg-indigo-100 text-indigo-800 @else bg-yellow-100 text-yellow-800 @endif', '{{ $reclamation->updated_at->format('d/m/Y') }}', '{{ $reclamation->nom_citoyen ?? 'Non spécifié' }}')"
                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Traiter la réclamation
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune réclamation trouvée</h3>
            <p class="mt-1 text-gray-500">Aucune réclamation n'est actuellement assignée.</p>
        </div>
    @endif

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         @click="showToast = false"
         class="fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50" 
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
    
    <!-- Loading Indicator -->
    <div x-show="isLoading" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
                <p class="text-gray-700 text-lg">Chargement en cours...</p>
            </div>
        </div>
    </div>
    
    <!-- Comment Delete Confirmation Modal -->
    <div x-show="showDeleteConfirmModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25" @click="showDeleteConfirmModal = false"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl max-w-md mx-auto">
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirmer la suppression</h3>
                    <button @click="showDeleteConfirmModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-700 mb-4">Êtes-vous sûr de vouloir supprimer ce commentaire ?</p>
                <div class="flex justify-end space-x-3">
                    <button @click="showDeleteConfirmModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                        Annuler
                    </button>
                    <button @click="deleteComment()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function reclamationManager() {
    return {
        // UI State
        isLoading: false,
        showOverlay: false,
        showDetailsPopup: false,
        showCommentForm: false,
        showDeleteConfirmModal: false,
        isCommentsExpanded: false,
        
        // Filter State
        statusFilter: 'all',
        
        // Toast Notification
        showToast: false,
        toastMessage: '',
        toastType: 'info', // 'success', 'error', 'info'
        
        // Comment State
        comments: [],
        displayComments: [],
        commentsLoading: false,
        commentText: '',
        commentError: '',
        commentSubmitting: false,
        commentToDeleteId: null,
        
        // Reclamation Data
        selectedReclamation: {
            id: null,
            titre: '',
            description: '',
            createdAt: '',
            priorite: '',
            status: '',
            originalStatus: '',
            statusClass: '',
            updatedAt: '',
            citoyen: ''
        },
        currentStatus: '',
        statusChanged: false,
        
        // CSRF Token
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        
        init() {
            // Initialize any flash messages or state from the server if needed
        },
        
        // Open reclamation details popup
        openReclamation(event, id, titre, description, createdAt, priorite, status, statusClass, updatedAt, citoyen) {
            this.selectedReclamation = {
                id,
                titre,
                description,
                createdAt,
                priorite,
                status,
                originalStatus: status,
                statusClass,
                updatedAt,
                citoyen
            };
            
            this.currentStatus = status;
            this.statusChanged = false;
            this.showOverlay = true;
            this.showDetailsPopup = true;
            this.fetchComments(id);
        },
        
        // Close all popups
        closeAllPopups() {
            this.showOverlay = false;
            this.showDetailsPopup = false;
            this.showCommentForm = false;
            this.showDeleteConfirmModal = false;
            this.isCommentsExpanded = false;
        },
        
        // Show toast notification
        showToastMessage(message, type = 'info') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },
        
        // Save status change
        saveStatus() {
            this.isLoading = true;
            this.showToastMessage('Envoi de l\'email en cours...', 'info');
            
            const formData = new FormData(document.getElementById('status-form'));
            
            fetch('/reclamations/' + this.selectedReclamation.id + '/status', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.showToastMessage('Statut mis à jour avec succès et email envoyé', 'success');
                    
                    // Give the user a moment to see the toast before reloading
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showToastMessage('Échec de l\'envoi de l\'email', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                this.showToastMessage('Échec de l\'envoi de l\'email: ' + error.message, 'error');
            })
            .finally(() => {
                this.isLoading = false;
            });
        },
        
        // Fetch comments for a reclamation
        fetchComments(reclamationId) {
            this.commentsLoading = true;
            
            fetch(`/reclamations/${reclamationId}/commentaires`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
                return response.json();
            })
            .then(comments => {
                this.comments = comments;
                this.updateDisplayComments();
            })
            .catch(error => {
                console.error('Error fetching comments:', error);
                this.showToastMessage('Erreur lors du chargement des commentaires', 'error');
            })
            .finally(() => {
                this.commentsLoading = false;
            });
        },
        
        // Update which comments to display based on expanded state
        updateDisplayComments() {
            this.displayComments = this.isCommentsExpanded ? this.comments : this.comments.slice(0, 2);
        },
        
        // Handle comment submission
        handleCommentSubmit(event) {
            event.preventDefault();
            this.commentSubmitting = true;
            this.commentError = '';
            
            const formData = new FormData(event.target);
            
            fetch(event.target.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            })
            .then(response => {
                // If the response is a redirect (302, 301), it's a success (Laravel redirect()->back())
                if (response.redirected || response.ok) {
                    this.commentText = '';
                    this.showCommentForm = false;
                    this.fetchComments(this.selectedReclamation.id);
                    this.showToastMessage('Commentaire ajouté avec succès', 'success');
                    return { success: true };
                }
                
                // If it's a 500 error, there's probably an issue with the notification
                // but the comment has been saved
                if (response.status === 500) {
                    this.commentText = '';
                    this.showCommentForm = false;
                    this.fetchComments(this.selectedReclamation.id);
                    this.showToastMessage('Commentaire enregistré mais notification non envoyée', 'info');
                    return { success: true, warning: 'Notification non envoyée' };
                }
                
                // For other errors, try to get the error message
                return response.text().then(text => {
                    try {
                        return { success: false, error: JSON.parse(text) };
                    } catch (e) {
                        throw new Error('Erreur réseau: ' + response.statusText);
                    }
                });
            })
            .then(result => {
                if (!result.success && result.error) {
                    this.commentError = result.error.message || 'Erreur lors de l\'envoi du commentaire';
                }
            })
            .catch(error => {
                console.error('Error submitting comment:', error);
                this.commentError = error.message || 'Erreur lors de l\'envoi du commentaire';
                this.showToastMessage('Erreur lors de l\'envoi du commentaire', 'error');
            })
            .finally(() => {
                this.commentSubmitting = false;
            });
        },
        
        // Confirm comment deletion
        confirmDeleteComment(commentId) {
            this.commentToDeleteId = commentId;
            this.showDeleteConfirmModal = true;
        },
        
        // Delete a comment
        deleteComment() {
            this.isLoading = true;
            this.showDeleteConfirmModal = false;
            
            fetch('/comments/' + this.commentToDeleteId, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-HTTP-Method-Override': 'DELETE'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
                this.fetchComments(this.selectedReclamation.id);
                this.showToastMessage('Commentaire supprimé avec succès', 'success');
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                this.showToastMessage('Erreur lors de la suppression du commentaire', 'error');
            })
            .finally(() => {
                this.isLoading = false;
                this.commentToDeleteId = null;
            });
        },
        
        // Filter reclamations by status
        filterReclamations() {
            const cards = document.querySelectorAll('.reclamation-card');
            const noResultsMsg = document.getElementById('no-results-message');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                
                if (this.statusFilter === 'all' || cardStatus === this.statusFilter) {
                    card.style.display = 'block';
                    // Add fade-in animation
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, 50);
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    this.showNoResultsMessage();
                }
            } else {
                if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }
            
            this.showToastMessage(`${visibleCount} réclamation(s) trouvée(s)`, 'info');
        },
        
        // Show no results message
        showNoResultsMessage() {
            const grid = document.getElementById('reclamations-grid');
            const message = document.createElement('div');
            message.id = 'no-results-message';
            message.className = 'col-span-full text-center py-12';
            message.innerHTML = `
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune réclamation trouvée</h3>
                <p class="mt-1 text-gray-500">Aucune réclamation ne correspond au filtre sélectionné.</p>
            `;
            grid.appendChild(message);
        },
        
        // Export to PDF
        exportToPDF() {
            this.isLoading = true;
            this.showToastMessage('Génération du PDF en cours...', 'info');
            
            // Collect visible reclamations data
            const visibleCards = document.querySelectorAll('.reclamation-card[style*="display: block"], .reclamation-card:not([style*="display: none"])');
            const reclamationsData = Array.from(visibleCards).map(card => ({
                titre: card.getAttribute('data-titre'),
                description: card.getAttribute('data-description'),
                status: card.getAttribute('data-status'),
                priorite: card.getAttribute('data-priorite'),
                date: card.getAttribute('data-date'),
                citoyen: card.getAttribute('data-citoyen')
            }));
            
            // Create form data for PDF generation
            const formData = new FormData();
            formData.append('reclamations', JSON.stringify(reclamationsData));
            formData.append('filter', this.statusFilter);
            
            // Send to server for PDF generation
            fetch('/agent/reclamations/export-pdf', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json, application/pdf',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur serveur: ' + response.status);
                }
                
                // Check if response is PDF
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/pdf')) {
                    return response.blob();
                } else {
                    // Fallback to client-side PDF generation
                    this.generatePDFClientSide(reclamationsData);
                    return null;
                }
            })
            .then(blob => {
                if (blob) {
                    // Download the PDF
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `reclamations_${this.statusFilter}_${new Date().getTime()}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    this.showToastMessage('PDF téléchargé avec succès', 'success');
                }
            })
            .catch(error => {
                console.error('Error generating PDF:', error);
                // Fallback to client-side generation
                this.generatePDFClientSide(reclamationsData);
            })
            .finally(() => {
                this.isLoading = false;
            });
        },
        
        // Generate PDF content structure
        generatePDFContent(reclamationsData) {
            const currentDate = new Date().toLocaleDateString('fr-FR');
            const filterText = this.statusFilter === 'all' ? 'Toutes' : this.statusFilter;
            
            return {
                title: 'Rapport des Réclamations',
                subtitle: `Filtre: ${filterText} - Généré le ${currentDate}`,
                data: reclamationsData,
                statistics: {
                    total: reclamationsData.length,
                    enAttente: reclamationsData.filter(r => r.status === 'en attente').length,
                    enCours: reclamationsData.filter(r => r.status === 'en cours').length,
                    resolues: reclamationsData.filter(r => r.status === 'résolue').length,
                    rejetees: reclamationsData.filter(r => r.status === 'rejetée').length
                }
            };
        },
        
        // Client-side PDF generation fallback
        generatePDFClientSide(reclamationsData) {
            // Simple HTML to print functionality
            const printWindow = window.open('', '_blank');
            const currentDate = new Date().toLocaleDateString('fr-FR');
            const filterText = this.statusFilter === 'all' ? 'Toutes' : this.statusFilter;
            
            let htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Rapport des Réclamations</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ccc; padding-bottom: 20px; }
                        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                        .stat-card { text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
                        .reclamation { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                        .status { padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
                        .status.en-attente { background-color: #fef3c7; color: #92400e; }
                        .status.en-cours { background-color: #e0e7ff; color: #3730a3; }
                        .status.resolue { background-color: #d1fae5; color: #065f46; }
                        .status.rejetee { background-color: #fee2e2; color: #991b1b; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Rapport des Réclamations</h1>
                        <p>Filtre: ${filterText} | Généré le ${currentDate}</p>
                        <p>Total: ${reclamationsData.length} réclamation(s)</p>
                    </div>
                    
                    <div class="stats">
                        <div class="stat-card">
                            <h3>En Attente</h3>
                            <p>${reclamationsData.filter(r => r.status === 'en attente').length}</p>
                        </div>
                        <div class="stat-card">
                            <h3>En Cours</h3>
                            <p>${reclamationsData.filter(r => r.status === 'en cours').length}</p>
                        </div>
                        <div class="stat-card">
                            <h3>Résolues</h3>
                            <p>${reclamationsData.filter(r => r.status === 'résolue').length}</p>
                        </div>
                        <div class="stat-card">
                            <h3>Rejetées</h3>
                            <p>${reclamationsData.filter(r => r.status === 'rejetée').length}</p>
                        </div>
                    </div>
                    
                    <div class="reclamations">
            `;
            
            reclamationsData.forEach((reclamation, index) => {
                htmlContent += `
                    <div class="reclamation">
                        <h3>${reclamation.titre}</h3>
                        <p><strong>Description:</strong> ${reclamation.description}</p>
                        <p><strong>Statut:</strong> <span class="status ${reclamation.status}">${reclamation.status}</span></p>
                        <p><strong>Priorité:</strong> ${reclamation.priorite}</p>
                        <p><strong>Date:</strong> ${new Date(reclamation.date).toLocaleDateString('fr-FR')}</p>
                        <p><strong>Citoyen:</strong> ${reclamation.citoyen}</p>
                    </div>
                `;
            });
            
            htmlContent += `
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(htmlContent);
            printWindow.document.close();
            
            // Auto print
            setTimeout(() => {
                printWindow.print();
                this.showToastMessage('PDF généré - Impression en cours', 'success');
            }, 500);
        }
    };
}

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['En Attente', 'En Cours', 'Résolues', 'Rejetées'],
            datasets: [{
                data: [{{ $enAttente }}, {{ $enCours }}, {{ $resolues }}, {{ $rejetees }}],
                backgroundColor: [
                    '#FCD34D', // Yellow for En Attente
                    '#818CF8', // Indigo for En Cours
                    '#34D399', // Green for Résolues
                    '#F87171'  // Red for Rejetées
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Priority Distribution Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const prioriteFaible = {{ $reclamations->where('priorite', 'faible')->count() }};
    const prioriteMoyenne = {{ $reclamations->where('priorite', 'moyenne')->count() }};
    const prioriteElevee = {{ $reclamations->where('priorite', 'élevée')->count() }};
    
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: ['Faible', 'Moyenne', 'Élevée'],
            datasets: [{
                label: 'Nombre de réclamations',
                data: [prioriteFaible, prioriteMoyenne, prioriteElevee],
                backgroundColor: [
                    '#10B981', // Green for Faible
                    '#F59E0B', // Orange for Moyenne
                    '#EF4444'  // Red for Élevée
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Timeline Chart (7 derniers jours)
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    const last7Days = [];
    const reclamationsCreated = [];
    const reclamationsResolved = [];
    
    // Generate last 7 days
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        last7Days.push(date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' }));
        
        // Simulated data - replace with actual database queries
        reclamationsCreated.push(Math.floor(Math.random() * 5) + 1);
        reclamationsResolved.push(Math.floor(Math.random() * 4) + 1);
    }
    
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: last7Days,
            datasets: [{
                label: 'Réclamations créées',
                data: reclamationsCreated,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Réclamations résolues',
                data: reclamationsResolved,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endsection