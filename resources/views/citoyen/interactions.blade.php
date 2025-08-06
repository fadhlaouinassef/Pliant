@extends('citoyen.dashboard')

@section('content')
<style>
    /* Animations personnalisées */
    .animation-delay-300 {
        animation-delay: 300ms;
    }
    .animation-delay-700 {
        animation-delay: 700ms;
    }
    /* Effet de flou personnalisé pour glassmorphism */
    .backdrop-blur-glass {
        backdrop-filter: blur(20px) saturate(180%);
        background-colrgba(255, 255, 255, 0.85);
    }
    /* Gradient animé */
    .gradient-animation {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
    /* Responsive improvements pour les cards */
    @media (max-width: 640px) {
        .footer-compact {
            flex-direction: column;
            gap: 0.5rem;
        }
        .interactions-compact {
            justify-content: center;
        }
        .details-btn-compact {
            align-self: stretch;
            justify-content: center;
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Overlay moderne avec animation -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/50 backdrop-blur-md z-40 transition-all duration-300"></div>
    
    <!-- Header Section avec animation -->citoyen.dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Overlay moderne avec animation -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/50 backdrop-blur-md z-40 transition-all duration-300"></div>
    
                                       <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 00-1.789 2.894l-3.5 7A2 2 0 008.736 21h4.018c.163 0 .326-.02.485-.06L17 20m-7-6V4h6m-6 10h6"></path>
                                    </svg>-- Header Section avec animation -->
    <div class="relative pt-8 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-6 shadow-lg transform hover:scale-105 transition-all duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent mb-4">
                    Mes Réclamations
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Suivez l'évolution de vos réclamations et interagissez avec la communauté
                </p>
            </div>
        </div>
    </div>
    
    <!-- Popup moderne pour voir les détails d'une réclamation -->
    <div id="detailsPopup" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay avec animation -->
            <div class="fixed inset-0 transition-opacity" onclick="closePopup('detailsPopup')">
                <div class="absolute inset-0 bg-gray-900/75 backdrop-blur-sm"></div>
            </div>

            <!-- Modal panel avec animations et glassmorphism -->
            <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white/95 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/20">
                
                <!-- Header avec gradient moderne -->
                <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-8">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-white/20 backdrop-blur rounded-full">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-1">Détails de la réclamation</h3>
                                    <p class="text-blue-100">Informations complètes et interactions</p>
                                </div>
                            </div>
                            <button onclick="closePopup('detailsPopup')" class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-full transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Titre et description dans le header -->
                        <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/20">
                            <h4 class="text-xl font-semibold text-white mb-3" id="reclamation-title"></h4>
                            <p class="text-blue-100 leading-relaxed" id="reclamation-description"></p>
                        </div>
                    </div>
                </div>

                <!-- Body du modal avec design en grid moderne -->
                <div class="p-8 space-y-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Informations</h5>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="reclamation-created-at"></span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span id="reclamation-priorite"></span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14 a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Agent: <span id="reclamation-agent"></span></span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Statut</h5>
                        <div class="flex items-center mb-3">
                            <span id="reclamation-status" class="px-3 py-1 text-xs font-medium rounded-full"></span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p id="reclamation-updated-at"></p>
                            <p>Progression: En attente de réparation</p>
                            <!-- Ajout des totaux -->
                            <div class="flex space-x-4 mt-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m0-10l3-7h6m-6 7h6"></path>
                                    </svg>
                                    <span>J'aime: <span id="reclamation-total-aime">0</span></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 00-1.789 2.894l-3.5 7A2 2 0 008.736 21h4.018a2 2 0 00.485-.06L17 20m-7-6V4h6m-6 10h6"></path>
                                    </svg>
                                    <span>Je n'aime pas: <span id="reclamation-total-pas-aime">0</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h5 class="font-medium text-gray-900 mb-2">Commentaires</h5>
                    <div class="text-sm text-gray-700 space-y-3" id="comments-section">
                        <p>Chargement des commentaires...</p>
                    </div>
                    
                    <button 
                        id="toggle-comments-button" 
                        class="hidden mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center"
                        onclick="toggleComments()"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        <span id="toggle-comments-text">Voir plus</span>
                    </button>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        id="like-button"
                        data-reclamation-id=""
                        onclick="handleInteraction('aime', this)"
                        class="px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-md shadow-sm hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m0-10l3-7h6m-6 7h6"></path>
                        </svg>
                        J'aime
                    </button>
                    <button 
                        id="dislike-button"
                        data-reclamation-id=""
                        onclick="handleInteraction('pas_aime', this)"
                        class="px-3 py-1.5 bg-red-100 text-red-800 text-sm font-medium rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 00-1.789 2.894l-3.5 7A2 2 0 008.736 21h4.018a2 2 0 00.485-.06L17 20m-7-6V4h6m-6 10h6"></path>
                        </svg>
                        Je n'aime pas
                    </button>
                    <button
                        onclick="closePopup('detailsPopup')"
                        class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Container principal des réclamations -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @if(isset($reclamations) && $reclamations->count() > 0)
            <!-- Grid responsive des réclamations -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($reclamations as $reclamation)
                    <div class="group relative bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg hover:shadow-2xl border border-white/20 overflow-hidden transform transition-all duration-500 hover:-translate-y-2 hover:scale-[1.02]">
                        
                        <!-- Indicateur de statut coloré -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r 
                            @if($reclamation->status == 'résolue') from-green-400 to-green-600
                            @elseif($reclamation->status == 'rejetée') from-red-400 to-red-600
                            @elseif($reclamation->status == 'en cours') from-blue-400 to-blue-600
                            @else from-orange-400 to-orange-600 @endif">
                        </div>

                        <!-- Header de la card -->
                        <div class="p-6 pb-4">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2 flex-1 mr-4">
                                    {{ $reclamation->titre }}
                                </h2>
                                <div class="relative">
                                    <span class="inline-flex items-center px-3 py-1.5 
                                        @if($reclamation->status == 'résolue') bg-green-100 text-green-800 border-green-200
                                        @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800 border-red-200
                                        @elseif($reclamation->status == 'en cours') bg-blue-100 text-blue-800 border-blue-200
                                        @else bg-orange-100 text-orange-800 border-orange-200 @endif
                                        text-xs font-semibold rounded-full border backdrop-blur-sm">
                                        
                                        <div class="w-2 h-2 mr-2 rounded-full 
                                            @if($reclamation->status == 'résolue') bg-green-500
                                            @elseif($reclamation->status == 'rejetée') bg-red-500
                                            @elseif($reclamation->status == 'en cours') bg-blue-500 animate-pulse
                                            @else bg-orange-500 @endif">
                                        </div>
                                        
                                        {{ ucfirst($reclamation->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Description avec gradient fade -->
                            <div class="relative">
                                <p class="text-gray-600 leading-relaxed mb-4 line-clamp-3">{{ Str::limit($reclamation->description, 150) }}</p>
                                <div class="absolute bottom-0 right-0 w-8 h-4 bg-gradient-to-l from-white/70 to-transparent pointer-events-none"></div>
                            </div>
                        </div>

                        <!-- Body avec informations -->
                        <div class="px-6 pb-4 space-y-3">
                            
                            <!-- Date et priorité -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center text-sm text-gray-600 bg-gray-50/50 rounded-xl p-3">
                                    <div class="p-1.5 bg-blue-100 rounded-lg mr-3">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Créée</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $reclamation->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 bg-gray-50/50 rounded-xl p-3">
                                    <div class="p-1.5 
                                        @if($reclamation->priorite == 'faible') bg-green-100
                                        @elseif($reclamation->priorite == 'moyenne') bg-yellow-100
                                        @else bg-red-100 @endif 
                                        rounded-lg mr-3">
                                        <svg class="w-3 h-3 
                                            @if($reclamation->priorite == 'faible') text-green-600
                                            @elseif($reclamation->priorite == 'moyenne') text-yellow-600
                                            @else text-red-600 @endif" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($reclamation->priorite == 'faible')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            @elseif($reclamation->priorite == 'moyenne')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Priorité</p>
                                        <p class="text-sm font-semibold 
                                            @if($reclamation->priorite == 'faible') text-green-700
                                            @elseif($reclamation->priorite == 'moyenne') text-yellow-700
                                            @else text-red-700 @endif">
                                            {{ ucfirst($reclamation->priorite) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Agent assigné -->
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50/50 rounded-xl p-3">
                                <div class="p-1.5 bg-purple-100 rounded-lg mr-3">
                                    <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium">Agent assigné</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        @if($reclamation->agent)
                                            {{ $reclamation->agent->name }}
                                        @else
                                            <span class="text-orange-600">Non assigné</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    
                    <!-- Footer avec interactions compactes -->
                    <div class="px-4 py-3 bg-gradient-to-r from-gray-50/80 to-white/80 backdrop-blur border-t border-gray-100">
                        
                        <!-- Layout responsive pour les interactions -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <!-- Métriques d'engagement compactes -->
                            <div class="flex items-center gap-4">
                                <!-- Likes compacts -->
                                <div class="flex items-center gap-1">
                                    <button 
                                        id="like-button-{{ $reclamation->id }}"
                                        data-reclamation-id="{{ $reclamation->id }}"
                                        onclick="handleInteraction('aime', this)"
                                        class="flex items-center gap-1 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg px-2 py-1.5 transition-all duration-200 text-xs font-medium"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m0-10l3-7h6m-6 7h6"/>
                                        </svg>
                                        <span id="total-aime-{{ $reclamation->id }}" class="font-semibold">{{ $reclamation->total_aime }}</span>
                                    </button>
                                </div>

                                <!-- Dislikes compacts -->
                                <div class="flex items-center gap-1">
                                    <button 
                                        id="dislike-button-{{ $reclamation->id }}"
                                        data-reclamation-id="{{ $reclamation->id }}"
                                        onclick="handleInteraction('pas_aime', this)"
                                        class="flex items-center gap-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg px-2 py-1.5 transition-all duration-200 text-xs font-medium"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 00-1.789 2.894l-3.5 7A2 2 0 008.736 21h4.018c.163 0 .326-.02.485-.06L17 20m-7-6V4h6m-6 10h6"/>
                                        </svg>
                                        <span id="total-pas-aime-{{ $reclamation->id }}" class="font-semibold">{{ $reclamation->total_pas_aime }}</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Bouton voir détails compact -->
                            <button 
                                onclick="openPopup('detailsPopup', this)"
                                data-id="{{ $reclamation->id }}"
                                data-titre="{{ $reclamation->titre }}"
                                data-description="{{ $reclamation->description }}"
                                data-created-at="{{ $reclamation->created_at->format('d/m/Y') }}"
                                data-priorite="{{ ucfirst($reclamation->priorite) }}"
                                data-status="{{ ucfirst($reclamation->status) }}"
                                data-status-class="@if($reclamation->status == 'résolue') bg-green-100 text-green-800 @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800 @elseif($reclamation->status == 'en cours') bg-blue-100 text-blue-800 @else bg-orange-100 text-orange-800 @endif"
                                data-updated-at="{{ $reclamation->updated_at->format('d/m/Y') }}"
                                data-agent="{{ $reclamation->agent ? $reclamation->agent->name : 'Non assigné' }}"
                                data-total-aime="{{ $reclamation->total_aime }}"
                                data-total-pas-aime="{{ $reclamation->total_pas_aime }}"
                                class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 py-2 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105 flex-shrink-0"
                            >
                                <!-- Effet de brillance -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                                
                                <div class="relative flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Détails</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                     
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    @else
            <!-- État vide moderne -->
            <div class="text-center py-20">
                <div class="max-w-md mx-auto">
                    <!-- Illustration moderne -->
                    <div class="relative mb-8">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <!-- Cercles décoratifs animés -->
                        <div class="absolute top-0 left-1/4 w-6 h-6 bg-blue-400/30 rounded-full animate-bounce"></div>
                        <div class="absolute top-8 right-1/4 w-4 h-4 bg-purple-400/30 rounded-full animate-bounce animation-delay-300"></div>
                        <div class="absolute bottom-4 left-1/3 w-3 h-3 bg-indigo-400/30 rounded-full animate-bounce animation-delay-700"></div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Aucune réclamation trouvée</h3>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        Vous n'avez pas encore créé de réclamation.<br>
                        <span class="text-sm text-gray-500">Commencez à faire entendre votre voix dans votre communauté.</span>
                    </p>
                    
                    <!-- Call to action -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-8 border border-blue-200/50">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Prêt à commencer ?</h4>
                        <p class="text-gray-600 mb-4">Créez votre première réclamation et rejoignez la conversation.</p>
                        <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                            Créer une réclamation
                        </button>
                    </div>
                </div>
            </div>
    @endif
</div>

<!-- Notification Popup -->
    <div id="notification-popup" class="hidden fixed bottom-4 right-4 z-50 max-w-sm bg-white rounded-lg shadow-xl border border-gray-200 p-4">
        <div class="flex items-center space-x-3">
            <div id="notification-icon" class="w-6 h-6 flex-shrink-0"></div>
            <span id="notification-message" class="text-sm text-gray-700"></span>
        </div>
    </div>

<script>
    // Set up CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isCommentsExpanded = false;
    let currentReclamationId = null;

    // Function to show notification popup
    function showNotification(status, message) {
        const popup = document.getElementById('notification-popup');
        const icon = document.getElementById('notification-icon');
        const messageElement = document.getElementById('notification-message');

        // Reset popup classes and content
        popup.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-gray-100');
        icon.className = 'w-6 h-6 flex-shrink-0';
        icon.innerHTML = '';
        messageElement.textContent = message;

        if (status === 'loading') {
            popup.classList.add('bg-gray-100');
            icon.innerHTML = `
                <svg class="animate-spin w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>`;
        } else if (status === 'success') {
            popup.classList.add('bg-green-100');
            icon.innerHTML = `
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
        } else if (status === 'error') {
            popup.classList.add('bg-red-100');
            icon.innerHTML = `
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
        }

        // Show popup
        popup.classList.remove('hidden');

        // Auto-hide after 3 seconds for success/error
        if (status !== 'loading') {
            setTimeout(() => {
                popup.classList.add('hidden');
            }, 3000);
        }
    }

    // Function to update interaction totals
    function updateTotals(reclamationId, totalAime, totalPasAime) {
        // Update card totals
        const totalAimeElement = document.getElementById(`total-aime-${reclamationId}`);
        const totalPasAimeElement = document.getElementById(`total-pas-aime-${reclamationId}`);
        if (totalAimeElement) totalAimeElement.textContent = totalAime;
        if (totalPasAimeElement) totalPasAimeElement.textContent = totalPasAime;

        // Update popup totals if open
        if (currentReclamationId == reclamationId) {
            document.getElementById('reclamation-total-aime').textContent = totalAime;
            document.getElementById('reclamation-total-pas-aime').textContent = totalPasAime;
        }

        // Update data attributes on view details button
        const viewDetailsButton = document.querySelector(`button[data-id="${reclamationId}"]`);
        if (viewDetailsButton) {
            viewDetailsButton.dataset.totalAime = totalAime;
            viewDetailsButton.dataset.totalPasAime = totalPasAime;
        }
    }

    // Function to open a popup and fetch interaction
    function openPopup(popupId, button = null) {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById(popupId).classList.remove('hidden');

        if (popupId === 'detailsPopup' && button) {
            // Populate popup with reclamation data
            document.getElementById('reclamation-title').textContent = button.dataset.titre;
            document.getElementById('reclamation-description').textContent = button.dataset.description;
            document.getElementById('reclamation-created-at').textContent = 'Créée le: ' + button.dataset.createdAt;
            document.getElementById('reclamation-priorite').textContent = 'Priorité: ' + button.dataset.priorite;
            document.getElementById('reclamation-status').textContent = button.dataset.status;
            document.getElementById('reclamation-status').className = 'px-3 py-1 text-xs font-medium rounded-full ' + button.dataset.statusClass;
            document.getElementById('reclamation-updated-at').textContent = 'Dernière mise à jour: ' + button.dataset.updatedAt;
            document.getElementById('reclamation-agent').textContent = button.dataset.agent;
            document.getElementById('reclamation-total-aime').textContent = button.dataset.totalAime;
            document.getElementById('reclamation-total-pas-aime').textContent = button.dataset.totalPasAime;

            // Set reclamation ID for buttons
            currentReclamationId = button.dataset.id;
            document.getElementById('like-button').dataset.reclamationId = currentReclamationId;
            document.getElementById('dislike-button').dataset.reclamationId = currentReclamationId;

            // Fetch comments
            fetchComments(currentReclamationId);

            // Fetch interaction status
            fetchInteraction(currentReclamationId);
        }
    }

    // Function to fetch interaction status
    function fetchInteraction(reclamationId) {
        fetch(`/interactions/${reclamationId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            const likeButton = document.getElementById('like-button');
            const dislikeButton = document.getElementById('dislike-button');
            const likeButtonCard = document.getElementById(`like-button-${reclamationId}`);
            const dislikeButtonCard = document.getElementById(`dislike-button-${reclamationId}`);

            // Reset button styles
            likeButton.className = 'px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-md shadow-sm hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center';
            dislikeButton.className = 'px-3 py-1.5 bg-red-100 text-red-800 text-sm font-medium rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center';
            likeButtonCard.className = 'text-green-600 hover:text-green-800 text-sm font-medium flex items-center';
            dislikeButtonCard.className = 'text-red-600 hover:text-red-800 text-sm font-medium flex items-center';

            // Apply blue style to selected button
            if (data.type === 'aime') {
                likeButton.className = 'px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center';
                likeButtonCard.className = 'text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center';
            } else if (data.type === 'pas_aime') {
                dislikeButton.className = 'px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center';
                dislikeButtonCard.className = 'text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center';
            }
        })
        .catch(error => {
            console.error('Error fetching interaction:', error);
            showNotification('error', 'Erreur lors de la vérification de l\'interaction.');
        });
    }

    // Function to handle like/dislike interactions
    function handleInteraction(type, button) {
        const reclamationId = button.dataset.reclamationId;
        const likeButton = document.getElementById(`like-button${reclamationId ? '-' + reclamationId : ''}`);
        const dislikeButton = document.getElementById(`dislike-button${reclamationId ? '-' + reclamationId : ''}`);

        // Show loading notification
        showNotification('loading', 'Enregistrement en cours...');

        // Fetch current interaction to determine action
        fetch(`/interactions/${reclamationId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const currentType = data.type;
            let url = '/interactions';
            let method = 'POST';
            let body = JSON.stringify({ id_reclamation: reclamationId, type: type });

            if (currentType === type) {
                // Delete interaction
                url = `/interactions/${reclamationId}`;
                method = 'DELETE';
                body = null;
            } else if (currentType) {
                // Update interaction
                url = `/interactions/${reclamationId}`;
                method = 'PUT';
                body = JSON.stringify({ type: type });
            }

            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: body
            });
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Update button styles
            if (type === 'aime') {
                likeButton.className = data.type ? 'text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center' : 'text-green-600 hover:text-green-800 text-sm font-medium flex items-center';
                dislikeButton.className = 'text-red-600 hover:text-red-800 text-sm font-medium flex items-center';
                
                if (reclamationId === currentReclamationId) {
                    document.getElementById('like-button').className = data.type ? 'px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center' : 'px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-md shadow-sm hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center';
                    document.getElementById('dislike-button').className = 'px-3 py-1.5 bg-red-100 text-red-800 text-sm font-medium rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center';
                }
            } else {
                dislikeButton.className = data.type ? 'text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center' : 'text-red-600 hover:text-red-800 text-sm font-medium flex items-center';
                likeButton.className = 'text-green-600 hover:text-green-800 text-sm font-medium flex items-center';
                
                if (reclamationId === currentReclamationId) {
                    document.getElementById('dislike-button').className = data.type ? 'px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center' : 'px-3 py-1.5 bg-red-100 text-red-800 text-sm font-medium rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center';
                    document.getElementById('like-button').className = 'px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-md shadow-sm hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center';
                }
            }

            // Update totals
            if (data.total_aime !== undefined && data.total_pas_aime !== undefined) {
                updateTotals(reclamationId, data.total_aime, data.total_pas_aime);
            }

            showNotification('success', data.message);
        })
        .catch(error => {
            console.error('Error handling interaction:', error);
            showNotification('error', 'Erreur lors de l\'enregistrement de l\'interaction.');
        });
    }

    // Function to fetch comments
    function fetchComments(reclamationId) {
        fetch(`/reclamations/${reclamationId}/commentaires`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.json();
        })
        .then(comments => {
            const commentsSection = document.getElementById('comments-section');
            const toggleButton = document.getElementById('toggle-comments-button');
            commentsSection.innerHTML = '';

            if (comments.length === 0) {
                commentsSection.innerHTML = '<p class="text-gray-500">Aucun commentaire pour cette réclamation.</p>';
                toggleButton.classList.add('hidden');
                return;
            }

            const commentsToShow = isCommentsExpanded ? comments : comments.slice(0, 2);

            commentsToShow.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'border-b border-gray-200 pb-3 mb-3';
                commentDiv.innerHTML = `
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-medium text-gray-900 text-xs">${comment.nom_ecrivain || comment.id_ecrivain}</span>
                        <span class="text-xs text-gray-500">${comment.created_at}</span>
                    </div>
                    <p class="text-gray-700 mb-2">${comment.commentaire}</p>
                `;
                commentsSection.appendChild(commentDiv);
            });

            if (comments.length > 2) {
                toggleButton.classList.remove('hidden');
                toggleButton.querySelector('#toggle-comments-text').textContent = isCommentsExpanded ? 'Voir moins' : 'Voir plus';
            } else {
                toggleButton.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error fetching comments:', error);
            document.getElementById('comments-section').innerHTML = 
                '<p class="text-red-500">Erreur lors du chargement des commentaires. Veuillez rafraîchir la page.</p>';
        });
    }

    // Function to toggle comments visibility
    function toggleComments() {
        isCommentsExpanded = !isCommentsExpanded;
        if (currentReclamationId) {
            fetchComments(currentReclamationId);
        }
    }

    // Function to close a popup
    function closePopup(popupId) {
        document.getElementById('overlay').classList.add('hidden');
        document.getElementById(popupId).classList.add('hidden');
        isCommentsExpanded = false;
        currentReclamationId = null;
    }

    // Close popups when clicking outside
    window.onclick = function(event) {
        const overlay = document.getElementById('overlay');
        if (event.target == overlay) {
            overlay.classList.add('hidden');
            document.querySelectorAll('[id$="Popup"]').forEach(popup => {
                popup.classList.add('hidden');
            });
            isCommentsExpanded = false;
            currentReclamationId = null;
        }
    }
</script>
@endsection