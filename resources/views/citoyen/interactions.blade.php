@extends('citoyen.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Overlay pour le Blur -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-40"></div>
    
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Mes Réclamations</h1>
    </div>
    
    <!-- Popup pour voir les détails d'une réclamation -->
    <div id="detailsPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow-xl border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Détails de la réclamation</h3>
                    <button onclick="closePopup('detailsPopup')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-2" id="reclamation-title"></h4>
                    <p class="text-gray-700" id="reclamation-description"></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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
    
    @if(isset($reclamations) && $reclamations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reclamations as $reclamation)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 
                    @if($reclamation->status == 'résolue') border-green-500
                    @elseif($reclamation->status == 'rejetée') border-red-500
                    @elseif($reclamation->status == 'en cours') border-blue-500
                    @else border-orange-500 @endif">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $reclamation->titre }}</h2>
                            <span class="px-3 py-1 
                                @if($reclamation->status == 'résolue') bg-green-100 text-green-800
                                @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800
                                @elseif($reclamation->status == 'en cours') bg-blue-100 text-blue-800
                                @else bg-orange-100 text-orange-800 @endif
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
                        
                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Créée le: {{ $reclamation->created_at->format('d/m/Y') }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-4">
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
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14 a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Agent: 
                                @if($reclamation->agent)
                                    {{ $reclamation->agent->name }}
                                @else
                                    Non assigné
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                        <div class="flex space-x-3 items-center">
                            <div class="flex items-center">
                                <button 
                                    id="like-button-{{ $reclamation->id }}"
                                    data-reclamation-id="{{ $reclamation->id }}"
                                    onclick="handleInteraction('aime', this)"
                                    class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m0-10l3-7h6m-6 7h6"></path>
                                    </svg>
                                    J'aime
                                </button>
                                <span id="total-aime-{{ $reclamation->id }}" class="text-sm text-gray-600 ml-1">{{ $reclamation->total_aime }}</span>
                            </div>
                            <div class="flex items-center">
                                <button 
                                    id="dislike-button-{{ $reclamation->id }}"
                                    data-reclamation-id="{{ $reclamation->id }}"
                                    onclick="handleInteraction('pas_aime', this)"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 00-1.789 2.894l-3.5 7A2 2 0 008.736 21h4.018a2 2 0 00.485-.06L17 20m-7-6V4h6m-6 10h6"></path>
                                    </svg>
                                    Je n'aime pas
                                </button>
                                <span id="total-pas-aime-{{ $reclamation->id }}" class="text-sm text-gray-600 ml-1">{{ $reclamation->total_pas_aime }}</span>
                            </div>
                        </div>
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
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Voir les détails
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
            <p class="mt-1 text-gray-500">Vous n'avez pas encore créé de réclamation.</p>
        </div>
    @endif
</div>

<!-- Notification Popup -->
    <div id="notification-popup" class="hidden fixed bottom-4 right-4 z-50 max-w-sm bg-white rounded-lg shadow-xl border border-gray-200 p-4 flex items-center space-x-3">
        <div id="notification-icon" class="w-6 h-6 flex-shrink-0"></div>
        <span id="notification-message" class="text-sm text-gray-700"></span>
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
                        <span class="font-medium text-gray-900 text-xs">${comment.id_ecrivain}</span>
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