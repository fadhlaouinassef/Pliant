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
</style>
@endsection

@section('title', 'Gestion des Avis')

@section('content')
<div x-data="avisCrud()" class="container mx-auto px-4 py-8">
    <!-- En-tête de la page avec statistiques -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold mb-2">Gestion des Avis</h1>
                <p class="text-indigo-100">Consultez et gérez tous les avis des utilisateurs</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ count($avis) }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Total des avis</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ number_format($avis->avg('note'), 1) }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Note moyenne</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $avis->where('etat', 'visible')->count() }}</div>
                    <div class="text-xs uppercase tracking-wide text-indigo-200">Avis visibles</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-primary hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Note Moyenne</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($avis->avg('note'), 1) }}</h3>
                    <div class="flex items-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($avis->avg('note')) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div class="rounded-full p-3 bg-indigo-100 text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-success hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Avis Positifs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $avis->where('note', '>=', 4)->count() }}</h3>
                    <p class="text-sm text-green-600 mt-2">{{ number_format($avis->where('note', '>=', 4)->count() / max(1, count($avis)) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-warning hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Avis Moyens</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $avis->where('note', '=', 3)->count() }}</h3>
                    <p class="text-sm text-yellow-600 mt-2">{{ number_format($avis->where('note', '=', 3)->count() / max(1, count($avis)) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 stat-card stat-card-danger hover-scale">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider">Avis Négatifs</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $avis->where('note', '<', 3)->count() }}</h3>
                    <p class="text-sm text-red-600 mt-2">{{ number_format($avis->where('note', '<', 3)->count() / max(1, count($avis)) * 100, 1) }}% du total</p>
                </div>
                <div class="rounded-full p-3 bg-red-100 text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Section des graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Graphique de distribution des notes -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribution des Notes</h2>
            <div class="h-64">
                <canvas id="ratingDistributionChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgba(239, 68, 68, 0.7)"></span>
                    <span>1 étoile</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgba(249, 115, 22, 0.7)"></span>
                    <span>2 étoiles</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgba(234, 179, 8, 0.7)"></span>
                    <span>3 étoiles</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgba(34, 197, 94, 0.7)"></span>
                    <span>4 étoiles</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgba(16, 185, 129, 0.7)"></span>
                    <span>5 étoiles</span>
                </div>
            </div>
        </div>
        
        <!-- Graphique de tendance des avis -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale chart-container">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Évolution des Avis</h2>
            <div class="h-64">
                <canvas id="ratingTrendChart"></canvas>
            </div>
            <div class="flex flex-wrap mt-4 justify-center">
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgb(79, 70, 229)"></span>
                    <span>Note moyenne</span>
                </div>
                <div class="data-label">
                    <span class="data-label-color" style="background-color: rgb(249, 115, 22)"></span>
                    <span>Nombre d'avis</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des avis -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des Avis</h2>
            <div class="flex space-x-2">
                <button @click="filterAvis('tous')" :class="{'bg-indigo-600 text-white': avisFilter === 'tous', 'bg-gray-200 text-gray-700': avisFilter !== 'tous'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Tous
                </button>
                <button @click="filterAvis('visible')" :class="{'bg-indigo-600 text-white': avisFilter === 'visible', 'bg-gray-200 text-gray-700': avisFilter !== 'visible'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Visibles
                </button>
                <button @click="filterAvis('non_visible')" :class="{'bg-indigo-600 text-white': avisFilter === 'non_visible', 'bg-gray-200 text-gray-700': avisFilter !== 'non_visible'}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Masqués
                </button>
            </div>
        </div>
        
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-sm uppercase">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nom Utilisateur</th>
                        <th class="py-3 px-6 text-left">Note</th>
                        <th class="py-3 px-6 text-left">Commentaire</th>
                        <th class="py-3 px-6 text-left">État</th>
                        <th class="py-3 px-6 text-left">Date</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <template x-if="filteredAvis.length === 0">
                        <tr>
                            <td colspan="7" class="py-6 text-center">Aucun avis disponible</td>
                        </tr>
                    </template>
                    <template x-for="(a, index) in filteredAvis" :key="a.id">
                        <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'" class="border-b border-gray-200 hover:bg-indigo-50 transition-colors">
                            <td class="py-3 px-6 text-left" x-text="a.id"></td>
                            <td class="py-3 px-6 text-left font-medium" x-text="a.nom_utilisateur"></td>
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center">
                                    <template x-for="i in 5" :key="i">
                                        <svg :class="i <= a.note ? 'text-yellow-400' : 'text-gray-300'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </template>
                                    <span class="ml-1 text-gray-600 text-sm" x-text="'(' + a.note + '/5)'"></span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div x-text="a.commentaire.length > 50 ? a.commentaire.substring(0, 50) + '...' : a.commentaire" class="truncate max-w-xs"></div>
                                <button 
                                    @click="showFullComment(a.commentaire)" 
                                    class="text-xs text-indigo-600 hover:text-indigo-800 mt-1"
                                    x-show="a.commentaire.length > 50">
                                    Voir plus
                                </button>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span :class="a.etat === 'visible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded-full text-xs font-medium">
                                    <span x-text="a.etat === 'visible' ? 'Visible' : 'Non visible'"></span>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left" x-text="formatDate(a.created_at)"></td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <template x-if="a.etat === 'non_visible'">
                                        <button @click="submitForm($event, 'Voulez-vous rendre cet avis visible?', 'Afficher l\'avis', a.id, 'visible')" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100 transition-colors" title="Rendre visible">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </template>
                                    <template x-if="a.etat === 'visible'">
                                        <button @click="submitForm($event, 'Voulez-vous masquer cet avis?', 'Masquer l\'avis', a.id, 'non_visible')" class="text-gray-600 hover:text-gray-900 p-1 rounded-full hover:bg-gray-100 transition-colors" title="Masquer">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </template>
                                    
                                    <button @click="submitForm($event, 'Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cet avis?', 'Supprimer l\'avis', a.id, 'delete')" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
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

    <!-- Indicateur de chargement -->
    <div x-show="isLoading" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500 mb-4"></div>
                <p class="text-gray-700 text-lg" x-text="loadingMessage">Chargement en cours...</p>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation -->
    <div x-show="showConfirmModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25" @click="showConfirmModal = false"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl max-w-md mx-auto">
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="confirmTitle">Confirmation</h3>
                    <button @click="showConfirmModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-700 mb-4" x-text="confirmMessage"></p>
                <div class="flex justify-end space-x-3">
                    <button @click="showConfirmModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                        Annuler
                    </button>
                    <button @click="confirmSubmit()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour afficher le commentaire complet -->
    <div x-show="showCommentModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black opacity-25" @click="showCommentModal = false"></div>
        <div class="bg-white rounded-lg p-6 z-10 shadow-xl max-w-md mx-auto w-full">
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Commentaire complet</h3>
                    <button @click="showCommentModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <p class="text-gray-700" x-text="fullComment"></p>
                </div>
                <div class="flex justify-end">
                    <button @click="showCommentModal = false" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function avisCrud() {
    return {
        isLoading: false,
        loadingMessage: 'Chargement en cours...',
        showToast: false,
        toastMessage: '',
        toastType: 'info', // 'success', 'error', 'info'
        showConfirmModal: false,
        confirmTitle: '',
        confirmMessage: '',
        formToSubmit: null,
        actionType: null,
        actionId: null,
        avisFilter: 'tous',
        showCommentModal: false,
        fullComment: '',
        allAvis: @json($avis),
        filteredAvis: [],
        ratingDistributionChart: null,
        ratingTrendChart: null,
        
        init() {
            // Filtrer les avis
            this.filteredAvis = [...this.allAvis];
            
            // Initialiser les graphiques
            this.$nextTick(() => {
                this.initCharts();
            });
            
            // Show flash messages if they exist
            @if(session('success'))
                this.showToastMessage('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToastMessage('{{ session('error') }}', 'error');
            @endif
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        
        filterAvis(filter) {
            this.avisFilter = filter;
            
            if (filter === 'tous') {
                this.filteredAvis = [...this.allAvis];
            } else {
                this.filteredAvis = this.allAvis.filter(avis => avis.etat === filter);
            }
        },
        
        showFullComment(comment) {
            this.fullComment = comment;
            this.showCommentModal = true;
        },
        
        initCharts() {
            // Graphique de distribution des notes
            const ratingCounts = [0, 0, 0, 0, 0];
            this.allAvis.forEach(avis => {
                if (avis.note >= 1 && avis.note <= 5) {
                    ratingCounts[avis.note - 1]++;
                }
            });
            
            const ctx1 = document.getElementById('ratingDistributionChart').getContext('2d');
            this.ratingDistributionChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['1 étoile', '2 étoiles', '3 étoiles', '4 étoiles', '5 étoiles'],
                    datasets: [{
                        label: 'Nombre d\'avis',
                        data: ratingCounts,
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(249, 115, 22, 0.7)',
                            'rgba(234, 179, 8, 0.7)',
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(16, 185, 129, 0.7)'
                        ],
                        borderColor: [
                            'rgb(239, 68, 68)',
                            'rgb(249, 115, 22)',
                            'rgb(234, 179, 8)',
                            'rgb(34, 197, 94)',
                            'rgb(16, 185, 129)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            
            // Graphique de tendance des avis
            // Créer des données pour les 6 derniers mois
            const months = [];
            const averages = [];
            const counts = [];
            
            // Obtenir les 6 derniers mois
            const today = new Date();
            for (let i = 5; i >= 0; i--) {
                const date = new Date(today.getFullYear(), today.getMonth() - i, 1);
                const monthName = date.toLocaleDateString('fr-FR', { month: 'short' });
                months.push(monthName);
                
                // Filtrer les avis pour ce mois
                const monthAvis = this.allAvis.filter(avis => {
                    const avisDate = new Date(avis.created_at);
                    return avisDate.getMonth() === date.getMonth() && avisDate.getFullYear() === date.getFullYear();
                });
                
                // Calculer la moyenne
                const sum = monthAvis.reduce((acc, avis) => acc + avis.note, 0);
                const avg = monthAvis.length > 0 ? sum / monthAvis.length : 0;
                averages.push(avg.toFixed(1));
                counts.push(monthAvis.length);
            }
            
            const ctx2 = document.getElementById('ratingTrendChart').getContext('2d');
            this.ratingTrendChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Note moyenne',
                            data: averages,
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Nombre d\'avis',
                            data: counts,
                            borderColor: 'rgb(249, 115, 22)',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            tension: 0.3,
                            fill: true,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Note moyenne'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Nombre d\'avis'
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        },
        
        showToastMessage(message, type = 'info') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },
        
        submitForm(event, confirmMessage, confirmTitle = 'Confirmation', id, action) {
            event.preventDefault();
            this.confirmTitle = confirmTitle;
            this.confirmMessage = confirmMessage;
            this.actionId = id;
            this.actionType = action;
            this.showConfirmModal = true;
        },
        
        confirmSubmit() {
            this.showConfirmModal = false;
            this.isLoading = true;
            this.loadingMessage = 'Traitement en cours...';
            
            // Créer et soumettre le formulaire dynamiquement
            const form = document.createElement('form');
            form.method = 'POST';
            
            if (this.actionType === 'delete') {
                form.action = '/admin/avis/' + this.actionId;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
            } else {
                form.action = '/admin/avis/' + this.actionId;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="etat" value="${this.actionType}">
                `;
            }
            
            document.body.appendChild(form);
            
            // Soumettre le formulaire après un court délai pour que le loader s'affiche
            setTimeout(() => {
                form.submit();
            }, 500);
        }
    };
}
</script>
@endsection
