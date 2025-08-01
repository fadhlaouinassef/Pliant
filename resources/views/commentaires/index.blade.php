@extends('layouts.app')

@section('title', 'Gestion des Commentaires')

@section('content')
<div x-data="commentaireCrud()" class="w-full mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Commentaires</h2>
            <button @click="openAddModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Ajouter Commentaire
            </button>
        </div>

        <!-- Tableau des commentaires -->
        <div class="w-full overflow-x-hidden">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réclamation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($commentaires as $commentaire)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($commentaire->user->image)
                                <img src="{{ asset('images/' . $commentaire->user->image) }}" alt="Photo profil" class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                    <span>{{ strtoupper(substr($commentaire->user->nom, 0, 1)) }}</span>
                                </div>
                                @endif
                                <div>
                                    <div>{{ $commentaire->user->nom }} {{ $commentaire->user->prenom }}</div>
                                    <div class="text-sm text-gray-500">{{ $commentaire->user->role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('reclamations.show', $commentaire->reclamation_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ Str::limit($commentaire->reclamation->titre, 30) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($commentaire->contenu, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $commentaire->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button @click="openEditModal({{ $commentaire->id }})" class="text-indigo-600 hover:text-indigo-900">Modifier</button>
                            <button @click="confirmDelete({{ $commentaire->id }})" class="text-red-600 hover:text-red-900">Supprimer</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $commentaires->links() }}
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
                    <form id="commentaireForm" :action="formAction" method="POST">
                        @csrf
                        <template x-if="currentItemId">
                            @method('PUT')
                        </template>
                        <input type="hidden" x-model="currentItemId" name="id">
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="reclamation_id" class="block text-sm font-medium text-gray-700">Réclamation</label>
                                <select id="reclamation_id" name="reclamation_id" x-model="currentItem.reclamation_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    @foreach($reclamations as $reclamation)
                                    <option value="{{ $reclamation->id }}">{{ $reclamation->titre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-6">
                                <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu</label>
                                <textarea name="contenu" id="contenu" x-model="currentItem.contenu" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
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
                                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteCommentaireForm" :action="deleteAction" method="POST">
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
    [x-cloak] { 
        display: none !important; 
    }
</style>

<script>
function commentaireCrud() {
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
        currentItemId: null,
        currentItem: {
            reclamation_id: '',
            contenu: ''
        },
        
        init() {
            // Écouter les messages flash
            @if(session('success'))
                this.showToastMessage('{{ session('success') }}', 'success');
            @endif
            
            @if(session('error'))
                this.showToastMessage('{{ session('error') }}', 'error');
            @endif
            
            // Si la liste des réclamations n'est pas vide, sélectionner la première par défaut
            @if(count($reclamations) > 0)
                this.currentItem.reclamation_id = {{ $reclamations->first()->id }};
            @endif
        },
        
        openAddModal() {
            this.modalTitle = 'Ajouter un commentaire';
            this.formAction = '{{ route("commentaires.store") }}';
            this.currentItemId = null;
            this.resetForm();
            this.isModalOpen = true;
        },
        
        openEditModal(commentaireId) {
            this.modalTitle = 'Modifier le commentaire';
            this.formAction = '{{ route("commentaires.update", ":id") }}'.replace(':id', commentaireId);
            this.currentItemId = commentaireId;
            
            const commentaire = @json($commentaires->items()).find(c => c.id === commentaireId);
            if (commentaire) {
                this.currentItem = {
                    reclamation_id: commentaire.reclamation_id,
                    contenu: commentaire.contenu
                };
            }
            
            this.isModalOpen = true;
        },
        
        confirmDelete(commentaireId) {
            this.deleteAction = '{{ route("commentaires.destroy", ":id") }}'.replace(':id', commentaireId);
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
                document.getElementById('commentaireForm').submit();
            }, 500);
        },
        
        executeDelete() {
            this.isLoading = true;
            this.showToastMessage('Suppression en cours...', 'info');
            
            // Soumettre le formulaire après un court délai pour que le toast s'affiche
            setTimeout(() => {
                document.getElementById('deleteCommentaireForm').submit();
            }, 500);
        },
        
        resetForm() {
            this.currentItem = {
                reclamation_id: @if(count($reclamations) > 0) {{ $reclamations->first()->id }} @else '' @endif,
                contenu: ''
            };
        }
    }
}
</script>
@endsection
