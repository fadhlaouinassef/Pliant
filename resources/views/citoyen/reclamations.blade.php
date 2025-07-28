@extends('citoyen.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Overlay pour le Blur -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-40"></div>
    
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Mes Réclamations</h1>
        <button 
            onclick="openPopup('reclamationPopup')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg flex items-center text-sm"
        >
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Ajouter une réclamation
        </button>
    </div>
    
    <!-- Popup pour ajouter une réclamation -->
    <div id="reclamationPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-md">
        <div class="bg-white rounded-lg shadow-xl border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Nouvelle réclamation</h3>
                    <button onclick="closePopup('reclamationPopup')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('reclamations.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleReclamationSubmit(event)">
                    @csrf
                    <div class="mb-4">
                        <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input 
                            type="text" 
                            name="titre" 
                            id="titre" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required 
                            placeholder="Entrez le titre de la réclamation"
                        >
                        @error('titre')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            rows="5" 
                            required 
                            placeholder="Décrivez votre réclamation ici..."
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                        <select 
                            name="priorite" 
                            id="priorite" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="elevee">Élevée</option>
                        </select>
                        @error('priorite')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="fichier" class="block text-sm font-medium text-gray-700">Fichier (optionnel)</label>
                        <input 
                            type="file" 
                            name="fichier" 
                            id="fichier" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            accept=".jpg,.jpeg,.png,.pdf"
                        >
                        @error('fichier')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="closePopup('reclamationPopup')"
                            class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Popup pour modifier une réclamation -->
    <div id="editReclamationPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-md">
        <div class="bg-white rounded-lg shadow-xl border border-gray-200">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Modifier la réclamation</h3>
                    <button onclick="closePopup('editReclamationPopup')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editReclamationForm" method="POST" enctype="multipart/form-data" onsubmit="handleReclamationUpdate(event)">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-reclamation-id">
                    <div class="mb-4">
                        <label for="edit-titre" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input 
                            type="text" 
                            name="titre" 
                            id="edit-titre" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required 
                            placeholder="Entrez le titre de la réclamation"
                        >
                        @error('titre')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea 
                            name="description" 
                            id="edit-description" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            rows="5" 
                            required 
                            placeholder="Décrivez votre réclamation ici..."
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit-priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                        <select 
                            name="priorite" 
                            id="edit-priorite" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required
                        >
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="elevee">Élevée</option>
                        </select>
                        @error('priorite')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit-fichier" class="block text-sm font-medium text-gray-700">Fichier (optionnel)</label>
                        <input 
                            type="file" 
                            name="fichier" 
                            id="edit-fichier" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            accept=".jpg,.jpeg,.png,.pdf"
                        >
                        @error('fichier')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="closePopup('editReclamationPopup')"
                            class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                                <span>Agent: <span id="reclamation-agent"></span></span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-2">Statut</h5>
                        <div class="flex items-center mb-3">
                            <span id="reclamation-status" class="px-3 py-1 text-xs font-medium rounded-full"></span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p id="reclamation-updated-at"></p>
                            <p>Progression: En attente de réparation</p>
                            <div id="existing-feedback" class="mt-2"></div>
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
                        class="hidden mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center"
                        onclick="toggleComments()"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        <span id="toggle-comments-text">Voir plus</span>
                    </button>
                    
                    <!-- Formulaire de commentaire -->
                    <div id="comment-form" class="hidden mt-4">
                        <form action="{{ route('comments.store') }}" method="POST" onsubmit="handleCommentSubmit(event)">
                            @csrf
                            <input type="hidden" name="id_reclamation" id="comment-reclamation-id">
                            <div class="mb-4">
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Votre commentaire</label>
                                <textarea 
                                    name="commentaire" 
                                    id="commentaire" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                    rows="4"
                                    placeholder="Saisir votre commentaire ici..."
                                    required
                                ></textarea>
                                @error('commentaire')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onclick="toggleCommentForm()"
                                    class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Envoyer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Feedback Section -->
                    <div id="feedback-section" class="hidden mt-4">
                        <form id="feedback-form" method="POST" onsubmit="handleFeedbackSubmit(event)">
                            @csrf
                            <input type="hidden" name="id_reclamation" id="feedback-reclamation-id">
                            <div class="mb-4">
                                <label for="satisfaction" class="block text-sm font-medium text-gray-700">Évaluation</label>
                                <div class="flex items-center space-x-2">
                                    <div class="star-rating" id="star-rating">
                                        <input type="radio" name="satisfaction_citoyen" id="star5" value="5" class="hidden" />
                                        <label for="star5" class="cursor-pointer text-xl text-gray-300 hover:text-yellow-400">★</label>
                                        <input type="radio" name="satisfaction_citoyen" id="star4" value="4" class="hidden" />
                                        <label for="star4" class="cursor-pointer text-xl text-gray-300 hover:text-yellow-400">★</label>
                                        <input type="radio" name="satisfaction_citoyen" id="star3" value="3" class="hidden" />
                                        <label for="star3" class="cursor-pointer text-xl text-gray-300 hover:text-yellow-400">★</label>
                                        <input type="radio" name="satisfaction_citoyen" id="star2" value="2" class="hidden" />
                                        <label for="star2" class="cursor-pointer text-xl text-gray-300 hover:text-yellow-400">★</label>
                                        <input type="radio" name="satisfaction_citoyen" id="star1" value="1" class="hidden" />
                                        <label for="star1" class="cursor-pointer text-xl text-gray-300 hover:text-yellow-400">★</label>
                                    </div>
                                </div>
                                @error('satisfaction_citoyen')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onclick="toggleFeedbackForm()"
                                    class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Envoyer l'évaluation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button
                        onclick="closePopup('detailsPopup')"
                        class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Fermer
                    </button>
                    <button
                        onclick="toggleCommentForm()"
                        class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Ajouter un commentaire
                    </button>
                    <button
                        id="feedback-button"
                        onclick="toggleFeedbackForm()"
                        class="hidden px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Donner un retour
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
                    
                    <div class="bg-gray-100 px-6 py-4 flex justify-between">
                        <div class="flex space-x-3">
                            <form action="{{ route('reclamations.destroy', $reclamation->id) }}" method="POST" onsubmit="handleReclamationDelete(event, {{ $reclamation->id }})">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                            @if(!$reclamation->agent_id)
                                <button 
                                    onclick="openEditPopup(this)"
                                    data-id="{{ $reclamation->id }}"
                                    data-titre="{{ $reclamation->titre }}"
                                    data-description="{{ $reclamation->description }}"
                                    data-priorite="{{ ucfirst($reclamation->priorite) }}"
                                    class="text-yellow-600 hover:text-yellow-800 text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Modifier
                                </button>
                            @endif
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
                            data-agent-id="{{ $reclamation->agent_id }}"
                            data-satisfaction="{{ $reclamation->satisfaction_citoyen }}"
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
            <div class="mt-6">
                <button 
                    onclick="openPopup('reclamationPopup')"
                    class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer une réclamation
                </button>
            </div>
        </div>
    @endif
</div>

<script>
    // Set up CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isCommentsExpanded = false;
    let isFeedbackFormVisible = false;
    let selectedRating = 0; // Track the selected rating

    // Function to update star ratings
    function updateStarRating(rating) {
        selectedRating = rating;
        const labels = document.querySelectorAll('#star-rating label');
        labels.forEach((label, index) => {
            if (index < rating) {
                label.classList.add('text-yellow-400');
                label.classList.remove('text-gray-300');
            } else {
                label.classList.remove('text-yellow-400');
                label.classList.add('text-gray-300');
            }
        });
    }

    // Function to display existing feedback
    function displayExistingFeedback(rating) {
        const feedbackDiv = document.getElementById('existing-feedback');
        if (rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<span class="text-xl ${i <= rating ? 'text-yellow-400' : 'text-gray-300'}">★</span>`;
            }
            feedbackDiv.innerHTML = `<p class="text-sm text-gray-700">Votre évaluation: ${stars}</p>`;
        } else {
            feedbackDiv.innerHTML = '';
        }
    }

    // Function to reset star ratings
    function resetStarRating() {
        selectedRating = 0;
        const labels = document.querySelectorAll('#star-rating label');
        labels.forEach(label => {
            label.classList.remove('text-yellow-400');
            label.classList.add('text-gray-300');
        });
        const inputs = document.querySelectorAll('#star-rating input');
        inputs.forEach(input => input.checked = false);
    }

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
            document.getElementById('reclamation-status').textContent = button.dataset.status;
            document.getElementById('reclamation-status').className = 'px-3 py-1 text-xs font-medium rounded-full ' + button.dataset.statusClass;
            document.getElementById('reclamation-updated-at').textContent = 'Dernière mise à jour: ' + button.dataset.updatedAt;
            document.getElementById('reclamation-agent').textContent = button.dataset.agent;
            document.getElementById('comment-reclamation-id').value = button.dataset.id;
            document.getElementById('feedback-reclamation-id').value = button.dataset.id;

            // Set feedback form action dynamically
            const feedbackForm = document.getElementById('feedback-form');
            feedbackForm.action = `/reclamations/${button.dataset.id}/feedback`;

            // Show feedback button only if agent_id is not null and satisfaction is not set
            const feedbackButton = document.getElementById('feedback-button');
            if (button.dataset.agentId && !button.dataset.satisfaction) {
                feedbackButton.classList.remove('hidden');
            } else {
                feedbackButton.classList.add('hidden');
            }
            
            // Display existing feedback
            displayExistingFeedback(button.dataset.satisfaction ? parseInt(button.dataset.satisfaction) : null);

            // Fetch comments for the reclamation
            fetchComments(button.dataset.id);

            // Update delete button action
            const deleteButton = document.getElementById('delete-button');
            deleteButton.onclick = function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/reclamations/' + button.dataset.id;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            };
        }
    }

    // Function to open edit popup
    function openEditPopup(button) {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('editReclamationPopup').classList.remove('hidden');

        // Populate edit form with reclamation data
        document.getElementById('edit-reclamation-id').value = button.dataset.id;
        document.getElementById('edit-titre').value = button.dataset.titre;
        document.getElementById('edit-description').value = button.dataset.description;
        document.getElementById('edit-priorite').value = button.dataset.priorite.toLowerCase();
        document.getElementById('editReclamationForm').action = `/reclamations/${button.dataset.id}`;
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

    // Function to handle comment form submission
    function handleCommentSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const reclamationId = document.getElementById('comment-reclamation-id').value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                form.reset();
                toggleCommentForm();
                fetchComments(reclamationId);
            } catch (e) {
                form.reset();
                toggleCommentForm();
                fetchComments(reclamationId);
            }
        })
        .catch(error => {
            console.error('Error submitting comment:', error);
            alert('Erreur lors de l\'envoi du commentaire: ' + error.message);
        });
    }

    // Function to handle feedback submission
    function handleFeedbackSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const reclamationId = document.getElementById('feedback-reclamation-id').value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                form.reset();
                resetStarRating();
                toggleFeedbackForm();
                window.location.reload();
            } catch (e) {
                form.reset();
                resetStarRating();
                toggleFeedbackForm();
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error submitting feedback:', error);
            alert('Erreur lors de l\'envoi de l\'évaluation: ' + error.message);
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

    // Function to handle reclamation deletion
    function handleReclamationDelete(event, reclamationId) {
        event.preventDefault();
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')) {
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
            closePopup('detailsPopup');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error deleting reclamation:', error);
            alert('Erreur lors de la suppression de la réclamation. Veuillez réessayer.');
        });
    }

    // Function to handle reclamation submission
    function handleReclamationSubmit(event) {
        event.preventDefault();
        const form = event.target;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            closePopup('reclamationPopup');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error submitting reclamation:', error);
            alert('Erreur lors de l\'envoi de la réclamation. Veuillez réessayer.');
        });
    }

    // Function to handle reclamation update
    function handleReclamationUpdate(event) {
        event.preventDefault();
        const form = event.target;
        const reclamationId = document.getElementById('edit-reclamation-id').value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-HTTP-Method-Override': 'PUT'
            },
            body: new FormData(form)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            closePopup('editReclamationPopup');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error updating reclamation:', error);
            alert('Erreur lors de la mise à jour de la réclamation. Veuillez réessayer.');
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
        document.getElementById('feedback-section').classList.add('hidden');
        isCommentsExpanded = false;
        isFeedbackFormVisible = false;
        document.getElementById('feedback-button').textContent = 'Donner un retour';
        resetStarRating();
    }

    // Function to toggle the comment form
    function toggleCommentForm() {
        const commentForm = document.getElementById('comment-form');
        commentForm.classList.toggle('hidden');
        if (!commentForm.classList.contains('hidden')) {
            document.getElementById('commentaire').focus();
        }
    }

    // Function to toggle the feedback form
    function toggleFeedbackForm() {
        isFeedbackFormVisible = !isFeedbackFormVisible;
        const feedbackSection = document.getElementById('feedback-section');
        const feedbackButton = document.getElementById('feedback-button');
        feedbackSection.classList.toggle('hidden');
        feedbackButton.textContent = isFeedbackFormVisible ? 'Envoyer l\'évaluation' : 'Donner un retour';
        if (!feedbackSection.classList.contains('hidden')) {
            resetStarRating();
        }
    }

    // Star rating functionality
    document.querySelectorAll('#star-rating input').forEach((input, index) => {
        input.addEventListener('change', () => {
            updateStarRating(parseInt(input.value));
        });
    });

    document.querySelectorAll('#star-rating label').forEach((label, index) => {
        label.addEventListener('mouseover', () => {
            const labels = document.querySelectorAll('#star-rating label');
            for (let i = 0; i < 5; i++) {
                if (i < 5 - index) {
                    labels[i].classList.add('text-yellow-400');
                    labels[i].classList.remove('text-gray-300');
                } else {
                    labels[i].classList.remove('text-yellow-400');
                    labels[i].classList.add('text-gray-300');
                }
            }
        });
        label.addEventListener('mouseout', () => {
            updateStarRating(selectedRating);
        });
    });

    // Close popups when clicking outside
    window.onclick = function(event) {
        const overlay = document.getElementById('overlay');
        if (event.target == overlay) {
            overlay.classList.add('hidden');
            document.querySelectorAll('[id$="Popup"]').forEach(popup => {
                popup.classList.add('hidden');
            });
            document.getElementById('comment-form').classList.add('hidden');
            document.getElementById('feedback-section').classList.add('hidden');
            isCommentsExpanded = false;
            isFeedbackFormVisible = false;
            document.getElementById('feedback-button').textContent = 'Donner un retour';
            resetStarRating();
        }
    }
</script>
@endsection