@extends('admin.dashboard')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="w-full mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Utilisateurs</h2>
            <button x-data="" x-on:click="$dispatch('open-add-modal')" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Ajouter Utilisateur
            </button>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Chart by Role -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Distribution par Rôle</h3>
                <div class="chart-container">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>

            <!-- Chart by Creation Date (Grouped by Month) -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Utilisateurs par Mois de Création</h3>
                <div class="chart-container">
                    <canvas id="creationDateChart"></canvas>
                </div>
            </div>

            <!-- Chart by Email Initial (Replaced Phone Length) -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Distribution par Initiale Email</h3>
                <div class="chart-container">
                    <canvas id="emailInitialChart"></canvas>
                </div>
            </div>

            <!-- Chart by Email Domain -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Distribution par Domaine Email</h3>
                <div class="chart-container">
                    <canvas id="emailDomainChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="w-full overflow-x-hidden">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone & Adresse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($utilisateurs as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->image)
                            <img src="{{ asset('images/' . $user->image) }}" alt="Photo profil" class="h-10 w-10 rounded-full object-cover">
                            @else
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span>💤️</span>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->prenom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>{{ $user->num_tlph }}</div>
                            <div class="text-sm text-gray-500">{{ $user->adresse }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role == 'admin' ? 'bg-green-100 text-green-800' : 
                                   ($user->role == 'agent' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-blue-100 text-blue-800') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button x-data="" x-on:click="$dispatch('open-edit-modal', {{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">Modifier</button>
                            <button x-data="" x-on:click="$dispatch('open-delete-modal', {{ $user->id }})" class="text-red-600 hover:text-red-900">Supprimer</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $utilisateurs->links() }}
        </div>
    </div>
</div>

<!-- Modal d'ajout -->
<div x-show="isModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-show="isModalOpen" @click.away="isModalOpen = false">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
                <form x-bind:action="formAction" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" x-model="currentUserId" name="id">
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="nom" id="nom" x-model="currentUser.nom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" name="prenom" id="prenom" x-model="currentUser.prenom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" x-model="currentUser.email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="num_tlph" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="num_tlph" id="num_tlph" x-model="currentUser.num_tlph" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                            <select id="role" name="role" x-model="currentUser.role" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="citoyen">Citoyen</option>
                                <option value="agent">Agent</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="adresse" id="adresse" x-model="currentUser.adresse" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-6" x-show="!currentUserId">
                            <label for="mdp" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" name="mdp" id="mdp" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Photo de profil</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="submitForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
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
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
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
                            <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form x-bind:action="deleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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

<!-- Modal d'édition (resté inchangé mais inclus pour complétude) -->
<div x-show="isModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-show="isModalOpen" @click.away="isModalOpen = false">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
                <form x-bind:action="formAction" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" x-model="currentUserId" name="id">
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="nom" id="nom" x-model="currentUser.nom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input type="text" name="prenom" id="prenom" x-model="currentUser.prenom" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" x-model="currentUser.email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="num_tlph" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="num_tlph" id="num_tlph" x-model="currentUser.num_tlph" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                            <select id="role" name="role" x-model="currentUser.role" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="citoyen">Citoyen</option>
                                <option value="agent">Agent</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <input type="text" name="adresse" id="adresse" x-model="currentUser.adresse" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="sm:col-span-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Photo de profil</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="submitForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Enregistrer
                </button>
                <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données des utilisateurs
    const users = @json($utilisateurs->items());
    console.log('Users data:', users);

    // 1. Graphique par rôle
    const roleCounts = users.reduce((acc, user) => {
        acc[user.role] = (acc[user.role] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('roleChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(roleCounts),
            datasets: [{
                data: Object.values(roleCounts),
                backgroundColor: ['#10B981', '#FBBF24', '#3B82F6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // 2. Graphique par date de création (groupé par mois)
    const monthCounts = users.reduce((acc, user) => {
        const date = new Date(user.created_at);
        const monthYear = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        acc[monthYear] = (acc[monthYear] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('creationDateChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthCounts),
            datasets: [{
                label: 'Nombre d\'utilisateurs',
                data: Object.values(monthCounts),
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 3. Graphique par initiale email (remplace longueur du numéro)
    const emailInitialCounts = users.reduce((acc, user) => {
        const initial = user.email.charAt(0).toUpperCase();
        acc[initial] = (acc[initial] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('emailInitialChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(emailInitialCounts),
            datasets: [{
                label: 'Nombre d\'utilisateurs',
                data: Object.values(emailInitialCounts),
                backgroundColor: '#10B981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 4. Graphique par domaine email
    const domainCounts = users.reduce((acc, user) => {
        const domain = user.email.split('@')[1];
        acc[domain] = (acc[domain] || 0) + 1;
        return acc;
    }, {});
    
    new Chart(document.getElementById('emailDomainChart'), {
        type: 'polarArea',
        data: {
            labels: Object.keys(domainCounts),
            datasets: [{
                data: Object.values(domainCounts),
                backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EC4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Code Alpine.js
    document.addEventListener('alpine:init', () => {
        Alpine.data('userCrud', () => ({
            isModalOpen: false,
            isDeleteModalOpen: false,
            modalTitle: '',
            formAction: '',
            deleteAction: '',
            currentUserId: null,
            currentUser: {
                nom: '',
                prenom: '',
                email: '',
                num_tlph: '',
                adresse: '',
                role: 'citoyen',
                image: ''
            },
            
            openAddModal() {
                this.modalTitle = 'Ajouter un utilisateur';
                this.formAction = '{{ route("admin.utilisateurs.store") }}';
                this.currentUserId = null;
                this.resetForm();
                this.isModalOpen = true;
            },
            
            openEditModal(userId) {
                this.modalTitle = 'Modifier l\'utilisateur';
                this.formAction = '{{ route("admin.utilisateurs.update", ":id") }}'.replace(':id', userId);
                this.currentUserId = userId;
                
                const user = users.find(u => u.id === userId);
                if (user) {
                    this.currentUser = {
                        nom: user.nom,
                        prenom: user.prenom,
                        email: user.email,
                        num_tlph: user.num_tlph,
                        adresse: user.adresse,
                        role: user.role,
                        image: user.image
                    };
                }
                
                this.isModalOpen = true;
            },
            
            confirmDelete(userId) {
                this.deleteAction = '{{ route("admin.utilisateurs.destroy", ":id") }}'.replace(':id', userId);
                this.isDeleteModalOpen = true;
            },
            
            submitForm() {
                document.querySelector('form').submit();
            },
            
            resetForm() {
                this.currentUser = {
                    nom: '',
                    prenom: '',
                    email: '',
                    num_tlph: '',
                    adresse: '',
                    role: 'citoyen',
                    image: ''
                };
            }
        }));
    });
});
</script>
@endsection