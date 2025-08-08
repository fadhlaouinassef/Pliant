@extends('citoyen.dashboard')

@section('content')
<style>
    /* Styles pour les loaders et animations */
    .loader-spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    
    .loader-overlay {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50">
    <!-- Overlay pour le Blur et Loading -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm z-40"></div>
    
    <!-- Loader Global avec contrôles JavaScript -->
    <div id="global-loader" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[9999] flex justify-center items-center" style="display: none;">
        <div class="bg-white rounded-xl p-8 shadow-2xl">
            <div class="flex items-center space-x-4">
                <div class="loader-spinner"></div>
                <span class="text-gray-600 font-medium">Traitement en cours...</span>
            </div>
        </div>
    </div>

    <!-- Popup de confirmation de suppression -->
    <div id="confirmDeletePopup" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[9998] flex justify-center items-center" style="display: none;">
        <div class="bg-white rounded-xl p-6 shadow-2xl max-w-md w-full mx-4">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-red-100 rounded-full mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
                    <p class="text-sm text-gray-600">Cette action est irréversible</p>
                </div>
            </div>
            <p class="text-gray-700 mb-6" id="delete-message">Êtes-vous sûr de vouloir supprimer cette réclamation ? Tous les commentaires associés seront également supprimés.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="cancelDelete()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header avec statistiques -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                        Mes Réclamations
                    </h1>
                    <p class="text-gray-600">Gérez et suivez vos réclamations</p>
                </div>
                <button 
                    onclick="openPopup('reclamationPopup')"
                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl flex items-center font-semibold shadow-lg hover:shadow-xl transform transition-all duration-200 hover:scale-105"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle réclamation
                </button>
            </div>

            <!-- Tableau de bord des statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total des réclamations -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-reclamations">{{ $reclamations->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Réclamations en attente -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
                            <p class="text-3xl font-bold text-orange-600" id="pending-reclamations">{{ $reclamations->where('status', 'en attente')->count() }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Réclamations en cours -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">En cours</p>
                            <p class="text-3xl font-bold text-blue-600" id="inprogress-reclamations">{{ $reclamations->where('status', 'en cours')->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Réclamations résolues -->
                <div class="bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Résolues</p>
                            <p class="text-3xl font-bold text-green-600" id="resolved-reclamations">{{ $reclamations->where('status', 'résolue')->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Graphique en secteurs pour les statuts -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par statut</h3>
                    <div class="relative h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Graphique en barres pour les priorités -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition par priorité</h3>
                    <div class="relative h-64">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- Popup pour ajouter une réclamation -->
    <div id="reclamationPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Header du formulaire -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white">Nouvelle réclamation</h3>
                        <p class="text-blue-100 text-sm">Décrivez votre problème en détail</p>
                    </div>
                    <button onclick="closePopup('reclamationPopup')" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Corps du formulaire -->
            <div class="p-6">
                <form action="{{ route('reclamations.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleReclamationSubmit(event)">
                    @csrf
                    <!-- Titre -->
                    <div class="mb-6">
                        <label for="titre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Titre de la réclamation
                        </label>
                        <input 
                            type="text" 
                            name="titre" 
                            id="titre" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white" 
                            required 
                            placeholder="Ex: Problème d'éclairage public"
                        >
                        @error('titre')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Description détaillée
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                            rows="4" 
                            required 
                            placeholder="Décrivez votre réclamation en détail : lieu, nature du problème, impact..."
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Priorité -->
                    <div class="mb-6">
                        <label for="priorite" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Niveau de priorité
                        </label>
                        <div class="relative">
                            <select 
                                name="priorite" 
                                id="priorite" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white appearance-none cursor-pointer" 
                                required
                            >
                                <option value="">Sélectionnez une priorité</option>
                                <option value="faible" class="text-green-600">🟢 Faible - Non urgent</option>
                                <option value="moyenne" class="text-yellow-600">🟡 Moyenne - Modérément urgent</option>
                                <option value="elevee" class="text-red-600">🔴 Élevée - Urgent</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('priorite')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Fichier -->
                    <div class="mb-6">
                        <label for="fichier" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Pièce jointe (optionnelle)
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                name="fichier" 
                                id="fichier" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                accept=".jpg,.jpeg,.png,.pdf"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, PDF (max 2MB)</p>
                        @error('fichier')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            onclick="closePopup('reclamationPopup')"
                            class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 flex items-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 flex items-center transform hover:scale-105"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Envoyer la réclamation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Popup pour modifier une réclamation -->
    <div id="editReclamationPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Header du formulaire -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-white">Modifier la réclamation</h3>
                        <p class="text-yellow-100 text-sm">Mettez à jour les informations</p>
                    </div>
                    <button onclick="closePopup('editReclamationPopup')" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Corps du formulaire -->
            <div class="p-6">
                <form id="editReclamationForm" method="POST" enctype="multipart/form-data" onsubmit="handleReclamationUpdate(event)">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-reclamation-id">
                    
                    <!-- Titre -->
                    <div class="mb-6">
                        <label for="edit-titre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Titre de la réclamation
                        </label>
                        <input 
                            type="text" 
                            name="titre" 
                            id="edit-titre" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white" 
                            required 
                            placeholder="Ex: Problème d'éclairage public"
                        >
                        @error('titre')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="edit-description" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Description détaillée
                        </label>
                        <textarea 
                            name="description" 
                            id="edit-description" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none" 
                            rows="4" 
                            required 
                            placeholder="Décrivez votre réclamation en détail..."
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Priorité -->
                    <div class="mb-6">
                        <label for="edit-priorite" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Niveau de priorité
                        </label>
                        <div class="relative">
                            <select 
                                name="priorite" 
                                id="edit-priorite" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white appearance-none cursor-pointer" 
                                required
                            >
                                <option value="faible" class="text-green-600">🟢 Faible - Non urgent</option>
                                <option value="moyenne" class="text-yellow-600">🟡 Moyenne - Modérément urgent</option>
                                <option value="elevee" class="text-red-600">🔴 Élevée - Urgent</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('priorite')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Fichier -->
                    <div class="mb-6">
                        <label for="edit-fichier" class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Nouvelle pièce jointe (optionnelle)
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                name="fichier" 
                                id="edit-fichier" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                accept=".jpg,.jpeg,.png,.pdf"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, PDF (max 2MB)</p>
                        @error('fichier')
                            <span class="text-red-500 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button
                            type="button"
                            onclick="closePopup('editReclamationPopup')"
                            class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 flex items-center"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-200 flex items-center transform hover:scale-105"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Popup pour voir les détails d'une réclamation -->
    <div id="detailsPopup" class="hidden fixed inset-4 z-50 overflow-hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-full max-w-6xl mx-auto">
            <!-- Header moderne avec dégradé - Plus compact -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-1" id="reclamation-title">Détails de la réclamation</h3>
                        <div class="flex items-center space-x-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full text-white bg-white bg-opacity-20 backdrop-blur-sm" id="reclamation-status">Statut</span>
                            <div class="flex items-center text-indigo-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs" id="reclamation-created-at">Date</span>
                            </div>
                        </div>
                    </div>
                    <button onclick="closePopup('detailsPopup')" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-all duration-200 ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Corps de la popup avec scroll optimisé -->
            <div class="flex-1 overflow-y-auto min-h-0">
                <div class="p-4">
                    <!-- Description principale - Plus compacte -->
                    <div class="mb-4">
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-4 border-l-4 border-indigo-500">
                            <h4 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Description
                            </h4>
                            <p class="text-sm text-gray-700 leading-relaxed" id="reclamation-description">Description de la réclamation</p>
                        </div>
                    </div>
                    
                    <!-- Informations détaillées en grille - Plus compactes -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <!-- Informations générales -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center text-sm">
                                <div class="p-1.5 bg-blue-100 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Informations générales
                            </h5>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Priorité</span>
                                    <span class="font-medium text-xs" id="reclamation-priorite">Priorité</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Agent assigné</span>
                                    <span class="font-medium text-xs" id="reclamation-agent">Agent</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5">
                                    <span class="text-xs text-gray-600">Dernière mise à jour</span>
                                    <span class="font-medium text-xs" id="reclamation-updated-at">Date</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statut et progression -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center text-sm">
                                <div class="p-1.5 bg-green-100 rounded-lg mr-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Suivi et évaluation
                            </h5>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                                    <span class="text-xs text-gray-600">Progression</span>
                                    <span class="text-xs text-blue-600">En cours de traitement</span>
                                </div>
                                <div id="existing-feedback" class="py-1.5"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section des commentaires modernisée - Hauteur fixe -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center text-sm">
                            <div class="p-1.5 bg-purple-100 rounded-lg mr-2">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            Discussion et commentaires
                        </h5>
                        <div class="bg-white rounded-lg p-3 border border-purple-200 h-24 flex flex-col">
                            <div class="text-sm text-gray-700 space-y-2 flex-1 overflow-y-auto" id="comments-section">
                                <div class="flex items-center justify-center py-4">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-purple-600"></div>
                                    <span class="ml-2 text-gray-500 text-xs">Chargement des commentaires...</span>
                                </div>
                            </div>
                            
                            <button 
                                id="toggle-comments-button" 
                                style="display: none"
                                class="mt-2 pt-2 border-t border-gray-100 text-purple-600 hover:text-purple-800 text-xs font-medium flex items-center justify-center transition-colors duration-200 flex-shrink-0"
                                onclick="toggleComments()"
                            >
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span id="toggle-comments-text">Voir plus</span>
                            </button>
                        </div>
                        
                        <!-- Formulaire de commentaire modernisé - Hauteur contrôlée -->
                        <div id="comment-form" class="hidden mt-3 max-h-28">
                            <form action="{{ route('comments.store') }}" method="POST" onsubmit="handleCommentSubmit(event)">
                                @csrf
                                <input type="hidden" name="id_reclamation" id="comment-reclamation-id">
                                <div class="mb-2">
                                    <label for="commentaire" class="block text-xs font-semibold text-gray-700 mb-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        Votre commentaire
                                    </label>
                                    <textarea 
                                        name="commentaire" 
                                        id="commentaire" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white resize-none text-sm" 
                                        rows="2"
                                        placeholder="Votre commentaire..."
                                        required
                                    ></textarea>
                                    @error('commentaire')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                    <div id="comment-error" class="text-red-500 text-xs mt-1 hidden"></div>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button
                                        type="button"
                                        onclick="toggleCommentForm()"
                                        class="px-3 py-1.5 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 text-xs"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200 transform hover:scale-105 text-xs"
                                    >
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Envoyer
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Section Feedback modernisée - Hauteur contrôlée -->
                        <div id="feedback-section" class="hidden mt-3 max-h-24">
                            <form id="feedback-form" method="POST" onsubmit="handleFeedbackSubmit(event)">
                                @csrf
                                <input type="hidden" name="id_reclamation" id="feedback-reclamation-id">
                                <div class="mb-2">
                                    <label for="satisfaction" class="block text-xs font-semibold text-gray-700 mb-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        Évaluation
                                    </label>
                                    <div class="flex items-center justify-center space-x-1 p-1 bg-white rounded-lg border border-gray-200">
                                        <div class="star-rating" id="star-rating">
                                            <input type="radio" name="satisfaction_citoyen" id="star5" value="5" class="hidden" />
                                            <label for="star5" class="cursor-pointer text-lg text-gray-300 hover:text-yellow-400 transition-colors duration-200">★</label>
                                            <input type="radio" name="satisfaction_citoyen" id="star4" value="4" class="hidden" />
                                            <label for="star4" class="cursor-pointer text-lg text-gray-300 hover:text-yellow-400 transition-colors duration-200">★</label>
                                            <input type="radio" name="satisfaction_citoyen" id="star3" value="3" class="hidden" />
                                            <label for="star3" class="cursor-pointer text-lg text-gray-300 hover:text-yellow-400 transition-colors duration-200">★</label>
                                            <input type="radio" name="satisfaction_citoyen" id="star2" value="2" class="hidden" />
                                            <label for="star2" class="cursor-pointer text-lg text-gray-300 hover:text-yellow-400 transition-colors duration-200">★</label>
                                            <input type="radio" name="satisfaction_citoyen" id="star1" value="1" class="hidden" />
                                            <label for="star1" class="cursor-pointer text-lg text-gray-300 hover:text-yellow-400 transition-colors duration-200">★</label>
                                        </div>
                                    </div>
                                    @error('satisfaction_citoyen')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button
                                        type="button"
                                        onclick="toggleFeedbackForm()"
                                        class="px-3 py-1.5 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 text-xs"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all duration-200 transform hover:scale-105 text-xs"
                                    >
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        Évaluer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer avec boutons d'action - Toujours visible -->
            <div class="bg-gray-50 border-t border-gray-200 px-4 py-3 flex-shrink-0">
                <div class="flex flex-wrap justify-end space-x-2">
                    <button
                        onclick="closePopup('detailsPopup')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 flex items-center text-sm"
                    >
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Fermer
                    </button>
                    <button
                        onclick="toggleCommentForm()"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 flex items-center transform hover:scale-105 text-sm"
                    >
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Commentaire
                    </button>
                    <button
                        id="feedback-button"
                        onclick="toggleFeedbackForm()"
                        style="display: none"
                        class="px-4 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 flex items-center transform hover:scale-105 text-sm"
                    >
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Évaluer
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Variables globales
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isCommentsExpanded = false;
    let isFeedbackFormVisible = false;
    let selectedRating = 0;
    let pendingDeleteAction = null;

    // Données pour les graphiques
    const statusData = {
        'en attente': {{ $reclamations->where('status', 'en attente')->count() }},
        'en cours': {{ $reclamations->where('status', 'en cours')->count() }},
        'résolue': {{ $reclamations->where('status', 'résolue')->count() }},
        'rejetée': {{ $reclamations->where('status', 'rejetée')->count() }}
    };

    const priorityData = {
        'faible': {{ $reclamations->where('priorite', 'faible')->count() }},
        'moyenne': {{ $reclamations->where('priorite', 'moyenne')->count() }},
        'elevee': {{ $reclamations->where('priorite', 'elevee')->count() }}
    };

    // Fonctions pour gérer les loaders
    function showLoader() {
        document.getElementById('global-loader').style.display = 'flex';
    }

    function hideLoader() {
        document.getElementById('global-loader').style.display = 'none';
    }

    // Fonctions pour gérer la popup de confirmation de suppression
    function showDeleteConfirm(message, action) {
        document.getElementById('delete-message').textContent = message;
        document.getElementById('confirmDeletePopup').style.display = 'flex';
        pendingDeleteAction = action;
    }

    function cancelDelete() {
        document.getElementById('confirmDeletePopup').style.display = 'none';
        pendingDeleteAction = null;
    }

    function confirmDelete() {
        if (pendingDeleteAction) {
            document.getElementById('confirmDeletePopup').style.display = 'none';
            pendingDeleteAction();
            pendingDeleteAction = null;
        }
    }

    // Initialisation des graphiques
    function initializeCharts() {
        // Graphique des statuts
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'En cours', 'Résolues', 'Rejetées'],
                datasets: [{
                    data: [statusData['en attente'], statusData['en cours'], statusData['résolue'], statusData['rejetée']],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
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
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Graphique des priorités
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['Faible', 'Moyenne', 'Élevée'],
                datasets: [{
                    label: 'Nombre de réclamations',
                    data: [priorityData['faible'], priorityData['moyenne'], priorityData['elevee']],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
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
    }

    // Fonction pour mettre à jour les étoiles
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

    // Fonction pour afficher le feedback existant
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

    // Fonction pour réinitialiser les étoiles
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

    // Fonction pour ouvrir les popups
    function openPopup(popupId, button = null) {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById(popupId).classList.remove('hidden');

        if (popupId === 'detailsPopup' && button) {
            // Remplir la popup avec les données
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

            // Configurer le formulaire de feedback
            const feedbackForm = document.getElementById('feedback-form');
            feedbackForm.action = `/reclamations/${button.dataset.id}/feedback`;

            // Afficher le bouton feedback si nécessaire
            const feedbackButton = document.getElementById('feedback-button');
            if (button.dataset.agentId && !button.dataset.satisfaction) {
                feedbackButton.style.display = 'inline-flex';
            } else {
                feedbackButton.style.display = 'none';
            }
            
            // Afficher le feedback existant
            displayExistingFeedback(button.dataset.satisfaction ? parseInt(button.dataset.satisfaction) : null);

            // Charger les commentaires
            fetchComments(button.dataset.id);
        }
    }

    // Fonction pour ouvrir la popup d'édition
    function openEditPopup(button) {
        document.getElementById('overlay').classList.remove('hidden');
        document.getElementById('editReclamationPopup').classList.remove('hidden');

        // Remplir le formulaire
        document.getElementById('edit-reclamation-id').value = button.dataset.id;
        document.getElementById('edit-titre').value = button.dataset.titre;
        document.getElementById('edit-description').value = button.dataset.description;
        document.getElementById('edit-priorite').value = button.dataset.priorite.toLowerCase();
        document.getElementById('editReclamationForm').action = `/reclamations/${button.dataset.id}`;
    }

    // Fonction pour charger les commentaires
    function fetchComments(reclamationId) {
        fetch(`/reclamations/${reclamationId}/commentaires`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(comments => {
            const commentsSection = document.getElementById('comments-section');
            const toggleButton = document.getElementById('toggle-comments-button');
            commentsSection.innerHTML = '';

            if (comments.length === 0) {
                commentsSection.innerHTML = '<p class="text-gray-500">Aucun commentaire pour cette réclamation.</p>';
                toggleButton.style.display = 'none';
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
                    ${comment.can_delete ? `
                    <div class="flex justify-end">
                        <button onclick="deleteComment(${comment.id}, ${reclamationId})" class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                    ` : ''}
                `;
                commentsSection.appendChild(commentDiv);
            });

            if (comments.length > 2) {
                toggleButton.style.display = 'flex';
                toggleButton.querySelector('#toggle-comments-text').textContent = isCommentsExpanded ? 'Voir moins' : 'Voir plus';
            } else {
                toggleButton.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des commentaires:', error);
            document.getElementById('comments-section').innerHTML = 
                '<p class="text-red-500">Erreur lors du chargement des commentaires.</p>';
        });
    }

    // Fonction pour supprimer un commentaire
    function deleteComment(commentId, reclamationId) {
        showDeleteConfirm(
            'Êtes-vous sûr de vouloir supprimer ce commentaire ?',
            () => {
                showLoader();
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);
                
                fetch(`/comments/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok || response.redirected) {
                        fetchComments(reclamationId);
                    } else {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Erreur lors de la suppression');
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression du commentaire: ' + error.message);
                })
                .finally(() => {
                    hideLoader();
                });
            }
        );
    }

    // Gestion des soumissions de formulaires avec loaders
    function handleReclamationSubmit(event) {
        event.preventDefault();
        showLoader();
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
            if (response.ok || response.redirected) {
                closePopup('reclamationPopup');
                location.reload();
            } else {
                return response.json().then(err => {
                    throw new Error(err.message || 'Erreur de soumission');
                });
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la soumission: ' + error.message);
        })
        .finally(() => {
            hideLoader();
        });
    }

    function handleReclamationUpdate(event) {
        event.preventDefault();
        showLoader();
        const form = event.target;
        const formData = new FormData(form);
        
        // Ajouter explicitement la méthode PUT
        formData.append('_method', 'PUT');

        fetch(form.action, {
            method: 'POST', // Laravel utilise POST avec _method pour PUT
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            if (response.ok || response.redirected) {
                closePopup('editReclamationPopup');
                location.reload();
            } else {
                return response.json().then(err => {
                    throw new Error(err.message || 'Erreur de mise à jour');
                });
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la mise à jour: ' + error.message);
        })
        .finally(() => {
            hideLoader();
        });
    }

    function handleReclamationDelete(event, reclamationId) {
        event.preventDefault();
        const form = event.target;
        
        showDeleteConfirm(
            'Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible et supprimera tous les commentaires associés.',
            () => {
                showLoader();
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok || response.redirected) {
                        location.reload();
                    } else {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Erreur de suppression');
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression: ' + error.message);
                })
                .finally(() => {
                    hideLoader();
                });
            }
        );
    }

    function handleCommentSubmit(event) {
        event.preventDefault();
        showLoader();
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
            if (response.ok || response.status === 500) {
                form.reset();
                toggleCommentForm();
                fetchComments(reclamationId);
            } else {
                throw new Error('Erreur de soumission');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi du commentaire');
        })
        .finally(() => {
            hideLoader();
        });
    }

    function handleFeedbackSubmit(event) {
        event.preventDefault();
        showLoader();
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
            if (response.ok) {
                form.reset();
                resetStarRating();
                toggleFeedbackForm();
                location.reload();
            } else {
                throw new Error('Erreur de soumission');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi de l\'évaluation');
        })
        .finally(() => {
            hideLoader();
        });
    }

    // Fonctions d'interface utilisateur
    function toggleComments() {
        isCommentsExpanded = !isCommentsExpanded;
        const reclamationId = document.getElementById('comment-reclamation-id').value;
        fetchComments(reclamationId);
    }

    function closePopup(popupId) {
        document.getElementById('overlay').classList.add('hidden');
        document.getElementById(popupId).classList.add('hidden');
        document.getElementById('comment-form').classList.add('hidden');
        document.getElementById('feedback-section').classList.add('hidden');
        isCommentsExpanded = false;
        isFeedbackFormVisible = false;
        resetStarRating();
    }

    function toggleCommentForm() {
        const commentForm = document.getElementById('comment-form');
        commentForm.classList.toggle('hidden');
        if (!commentForm.classList.contains('hidden')) {
            document.getElementById('commentaire').focus();
        }
    }

    function toggleFeedbackForm() {
        isFeedbackFormVisible = !isFeedbackFormVisible;
        const feedbackSection = document.getElementById('feedback-section');
        feedbackSection.classList.toggle('hidden');
        if (!feedbackSection.classList.contains('hidden')) {
            resetStarRating();
        }
    }

    // Configuration des étoiles
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les graphiques
        initializeCharts();

        // Configuration du système d'étoiles
        document.querySelectorAll('#star-rating input').forEach((input) => {
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
    });

    // Fermer les popups en cliquant en dehors
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
            resetStarRating();
        }
    }
</script>
@endsection