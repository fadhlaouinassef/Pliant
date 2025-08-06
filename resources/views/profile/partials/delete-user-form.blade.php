<section class="space-y-6" x-data="{ showDeleteModal: false, confirmText: '', canDelete: false }" 
         @keydown.escape="showDeleteModal = false">
    
    <!-- Avertissement de sécurité -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-red-800 mb-2">Suppression du compte</h3>
                <div class="text-sm text-red-700 space-y-2">
                    <p class="font-medium">⚠️ Cette action est irréversible et aura les conséquences suivantes :</p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>Suppression définitive de toutes vos données personnelles</li>
                        <li>Perte de l'accès à votre compte et vos réclamations</li>
                        <li>Suppression de votre historique d'interactions</li>
                        <li>Impossibilité de récupérer ces informations</li>
                    </ul>
                    <p class="font-medium text-red-800 mt-4">
                        💡 Nous vous recommandons de télécharger vos données importantes avant de procéder.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions de sauvegarde recommandées -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h4 class="text-sm font-semibold text-blue-800 mb-2">Actions recommandées avant la suppression</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <button class="flex items-center p-3 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-blue-700">Télécharger mes données</span>
            </button>
            <button class="flex items-center p-3 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 2a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
                <span class="text-sm text-blue-700">Exporter mes réclamations</span>
            </button>
        </div>
    </div>

    <!-- Bouton de suppression -->
    <div class="flex justify-center pt-4">
        <button @click="showDeleteModal = true"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            Supprimer définitivement mon compte
        </button>
    </div>

    <!-- Modal de confirmation -->
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showDeleteModal = false"></div>

            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <!-- Header -->
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 text-center mb-2">
                        Confirmer la suppression du compte
                    </h3>

                    <p class="text-sm text-gray-600 text-center mb-6">
                        Cette action supprimera définitivement votre compte et toutes les données associées. 
                        Cette opération ne peut pas être annulée.
                    </p>

                    <!-- Vérification par texte -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Pour confirmer, tapez <span class="font-mono bg-gray-100 px-2 py-1 rounded text-red-600">SUPPRIMER</span>
                        </label>
                        <input type="text" 
                               x-model="confirmText"
                               @input="canDelete = confirmText === 'SUPPRIMER'"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="Tapez SUPPRIMER en majuscules">
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <label for="delete_password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe actuel
                        </label>
                        <input id="delete_password" 
                               name="password" 
                               type="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="Votre mot de passe actuel"
                               required>
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                @click="showDeleteModal = false; confirmText = ''; canDelete = false"
                                class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" 
                                :disabled="!canDelete"
                                :class="canDelete ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="flex-1 px-4 py-3 font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
