@extends('agent.dashboard')

@section('content')
<div x-data="reclamationManager()" class="container mx-auto px-4 py-8">
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
        }
    };
}
</script>
@endsection