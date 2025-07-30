@extends('agent.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Overlay for Blur -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-40"></div>
    
    
    
    <!-- Popup for viewing reclamation details -->
    <div id="detailsPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow-xl border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Détails de la réclamation</h3>
                    <div class="flex space-x-2">
                        <button id="save-status-button" class="hidden px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500" onclick="saveStatus()">
                            Enregistrer
                        </button>
                        <button onclick="closePopup('detailsPopup')" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-2" id="reclamation-title"></h4>
                    <p class="text-gray-700" id="reclamation-description"></p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-100 p-4 rounded-lg">
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
                                <span>Citoyen ID: <span id="reclamation-citoyen"></span></span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Statut</h5>
                        <div class="flex items-center mb-3">
                            <form id="status-form" action="" method="POST">
                                @csrf
                                @method('PATCH')
                                <select 
                                    name="status" 
                                    id="reclamation-status" 
                                    class="px-3 py-1 text-xs font-medium rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    onchange="showSaveButton()"
                                >
                                    <option value="en attente">En attente</option>
                                    <option value="en cours">En cours</option>
                                    <option value="résolue">Résolue</option>
                                    <option value="rejetée">Rejetée</option>
                                </select>
                            </form>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p id="reclamation-updated-at"></p>
                            <p>Progression: En attente de traitement</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h5 class="font-medium text-gray-900 mb-2">Commentaires</h5>
                    <div class="text-sm text-gray-700 space-y-3" id="comments-section">
                        <p>Chargement des commentaires...</p>
                    </div>
                    
                    <button 
                        id="toggle-comments-button" 
                        class="hidden mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center"
                        onclick="toggleComments()"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        <span id="toggle-comments-text">Voir plus</span>
                    </button>
                    
                    <!-- Comment form -->
                    <div id="comment-form" class="hidden mt-4">
                        <form action="{{ route('comments.store') }}" method="POST" onsubmit="handleCommentSubmit(event)">
                            @csrf
                            <input type="hidden" name="id_reclamation" id="comment-reclamation-id">
                            <div class="mb-4">
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Votre commentaire</label>
                                <textarea 
                                    name="commentaire" 
                                    id="commentaire" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                                    rows="4"
                                    placeholder="Saisir votre commentaire ici..."
                                    required
                                ></textarea>
                                @error('commentaire')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                <!-- Error message for AJAX errors -->
                                <div id="comment-error" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onclick="toggleCommentForm()"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <button 
                        onclick="closePopup('detailsPopup')"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Fermer
                    </button>
                    <button
                        onclick="toggleCommentForm()"
                        class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        Ajouter un commentaire
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
                    @elseif($reclamation->status == 'en cours') border-indigo-500
                    @else border-yellow-500 @endif">
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
                            onclick="openPopup('detailsPopup', this)"
                            data-id="{{ $reclamation->id }}"
                            data-titre="{{ $reclamation->titre }}"
                            data-description="{{ $reclamation->description }}"
                            data-created-at="{{ $reclamation->created_at->format('d/m/Y') }}"
                            data-priorite="{{ ucfirst($reclamation->priorite) }}"
                            data-status="{{ $reclamation->status }}"
                            data-status-class="@if($reclamation->status == 'résolue') bg-green-100 text-green-800 @elseif($reclamation->status == 'rejetée') bg-red-100 text-red-800 @elseif($reclamation->status == 'en cours') bg-indigo-100 text-indigo-800 @else bg-yellow-100 text-yellow-800 @endif"
                            data-updated-at="{{ $reclamation->updated_at->format('d/m/Y') }}"
                            data-citoyen="{{ $reclamation->nom_citoyen ?? 'Non spécifié' }}"
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

    <!-- Notification Popup -->
    <div id="notification-popup" class="hidden fixed bottom-4 right-4 z-50 max-w-sm bg-white rounded-lg shadow-xl border border-gray-200 p-4 flex items-center space-x-3">
        <div id="notification-icon" class="w-6 h-6 flex-shrink-0"></div>
        <span id="notification-message" class="text-sm text-gray-700"></span>
    </div>
</div>


<script>
    // Set up CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isCommentsExpanded = false;
    let originalStatus = '';

    // Function to open a popup
    function openPopup(popupId, button = null) {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById(popupId).classList.remove('hidden');

        if (popupId === 'detailsPopup' && button) {
            // Populate popup with reclamation data
            document.getElementById('reclamation-title').textContent = button.dataset.titre;
            document.getElementById('reclamation-description').textContent = button.dataset.description;
            document.getElementById('reclamation-created-at').textContent = 'Créée le: ' + button.dataset.createdAt;
            document.getElementById('reclamation-priorite').textContent = 'Priorité: ' + button.dataset.priorite;
            document.getElementById('reclamation-status').value = button.dataset.status;
            document.getElementById('reclamation-status').className = 'px-3 py-1 text-xs font-medium rounded-full ' + button.dataset.statusClass;
            document.getElementById('reclamation-updated-at').textContent = 'Dernière mise à jour: ' + button.dataset.updatedAt;
            document.getElementById('reclamation-citoyen').textContent = button.dataset.citoyen;
            document.getElementById('comment-reclamation-id').value = button.dataset.id;

            // Set form action for status update
            document.getElementById('status-form').action = '/reclamations/' + button.dataset.id + '/status';

            // Store original status
            originalStatus = button.dataset.status;

            // Hide save button initially
            document.getElementById('save-status-button').classList.add('hidden');

            // Fetch comments
            fetchComments(button.dataset.id);
        }
    }

    // Function to show save button when status changes
    function showSaveButton() {
        const currentStatus = document.getElementById('reclamation-status').value;
        const saveButton = document.getElementById('save-status-button');
        if (currentStatus !== originalStatus) {
            saveButton.classList.remove('hidden');
        } else {
            saveButton.classList.add('hidden');
        }
    }

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
            messageElement.textContent = 'Envoi de l\'email en cours...';
        } else if (status === 'success') {
            popup.classList.add('bg-green-100');
            icon.innerHTML = `
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
            messageElement.textContent = 'Statut mis à jour avec succès et email envoyé';
        } else if (status === 'error') {
            popup.classList.add('bg-red-100');
            icon.innerHTML = `
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
            messageElement.textContent = 'Échec de l\'envoi de l\'email';
        }

        // Auto-close after 3 seconds (optional)
        if (status !== 'loading') {
            setTimeout(() => closePopup('notification-popup'), 3000);
        }
    }

    // Function to save status
    function saveStatus() {
        const form = document.getElementById('status-form');
        showNotification('loading', 'Envoi de l\'email en cours...'); // Show loading state

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-HTTP-Method-Override': 'PATCH'
            },
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('success', 'Statut mis à jour avec succès et email envoyé');
            } else {
                showNotification('error', 'Échec de l\'envoi de l\'email');
            }
            window.location.reload(); // Reload after notification
        })
        .catch(error => {
            console.error('Error updating status:', error);
            showNotification('error', 'Échec de l\'envoi de l\'email');
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
                        <span class="font-medium text-gray-900">${comment.id_ecrivain}</span>
                        <span class="text-xs text-gray-500">${comment.created_at}</span>
                    </div>
                    <p class="text-gray-700 mb-2">${comment.commentaire}</p>
                    ${comment.can_delete ? `
                    <div class="flex justify-end">
                        <form action="/comments/${comment.id}" method="POST" onsubmit="handleCommentDelete(event, ${reclamationId})">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                    ` : ''}
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

    // Function to handle comment submission
    function handleCommentSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const reclamationId = document.getElementById('comment-reclamation-id').value;
        
        // Désactiver le bouton d'envoi pour éviter les soumissions multiples
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Envoi en cours...';
        }
        
        // Effacer les messages d'erreur précédents
        const errorEl = document.getElementById('comment-error');
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: new FormData(form)
        })
        .then(response => {
            // Si la réponse est une redirection (302, 301), c'est une réussite (Laravel redirect()->back())
            if (response.redirected || response.ok) {
                form.reset();
                toggleCommentForm();
                fetchComments(reclamationId);
                return { success: true };
            }
            
            // Si c'est une erreur 500, il y a probablement un problème avec la notification
            // mais le commentaire a été enregistré
            if (response.status === 500) {
                form.reset();
                toggleCommentForm();
                fetchComments(reclamationId);
                console.warn('Commentaire enregistré mais erreur de notification');
                return { success: true, warning: 'Notification non envoyée' };
            }
            
            // Pour les autres erreurs, tenter de récupérer le message d'erreur
            return response.text().then(text => {
                try {
                    return { success: false, error: JSON.parse(text) };
                } catch (e) {
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
            });
        })
        .then(result => {
            if (result.success) {
                // Le commentaire a été enregistré avec succès
                if (result.warning) {
                    console.warn(result.warning);
                }
            } else if (result.error) {
                // Afficher l'erreur
                if (errorEl) {
                    errorEl.textContent = result.error.message || 'Erreur lors de l\'envoi du commentaire';
                    errorEl.classList.remove('hidden');
                }
            }
        })
        .catch(error => {
            console.error('Error submitting comment:', error);
            
            // Afficher un message d'erreur
            if (errorEl) {
                errorEl.textContent = error.message || 'Erreur lors de l\'envoi du commentaire';
                errorEl.classList.remove('hidden');
            } else {
                alert('Erreur lors de l\'envoi du commentaire: ' + error.message);
            }
        })
        .finally(() => {
            // Réactiver le bouton
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Envoyer';
            }
        });
    }

    // Function to handle comment deletion
    function handleCommentDelete(event, reclamationId) {
        event.preventDefault();
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            return;
        }

        const form = event.target;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-HTTP-Method-Override': 'DELETE'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            fetchComments(reclamationId);
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Erreur lors de la suppression du commentaire. Veuillez réessayer.');
        });
    }

    // Function to toggle comments visibility
    function toggleComments() {
        isCommentsExpanded = !isCommentsExpanded;
        const reclamationId = document.getElementById('comment-reclamation-id').value;
        fetchComments(reclamationId);
    }

    // Function to close a popup
    function closePopup(popupId) {
        document.getElementById('overlay').classList.add('hidden');
        document.getElementById(popupId).classList.add('hidden');
        document.getElementById('comment-form').classList.add('hidden');
        document.getElementById('save-status-button').classList.add('hidden');
        isCommentsExpanded = false;
    }

    // Function to toggle the comment form
    function toggleCommentForm() {
        const commentForm = document.getElementById('comment-form');
        commentForm.classList.toggle('hidden');
        if (!commentForm.classList.contains('hidden')) {
            document.getElementById('commentaire').focus();
        }
    }

    // Close popups when clicking outside
    window.onclick = function(event) {
        const overlay = document.getElementById('overlay');
        if (event.target == overlay) {
            overlay.classList.add('hidden');
            document.querySelectorAll('[id$="Popup"]').forEach(popup => {
                popup.classList.add('hidden');
            });
            document.getElementById('comment-form').classList.add('hidden');
            document.getElementById('save-status-button').classList.add('hidden');
            isCommentsExpanded = false;
        }
    }
</script>
@endsection