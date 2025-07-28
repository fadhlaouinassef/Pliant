@extends('agent.dashboard')

@section('title', 'Liste des Agents')

@section('content')
<div class="w-full mx-auto" x-data="agentList">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Sort Bar -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Liste des Agents</h2>
            <div class="flex space-x-4">
                <button @click="sortBy('nom')" class="px-4 py-2 bg-white rounded-md shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" :class="{'bg-indigo-100': sortKey === 'nom'}">
                    Nom
                </button>
                <button @click="sortBy('email')" class="px-4 py-2 bg-white rounded-md shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500" :class="{'bg-indigo-100': sortKey === 'email'}">
                    Email
                </button>
            </div>
        </div>

        <!-- Search Input -->
        <div class="mb-6">
            <input type="text" x-model="searchQuery" :placeholder="'Rechercher par ' + (sortKey === 'nom' ? 'nom' : 'email') + '...'" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Agents Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-if="filteredAndSortedAgents.length === 0">
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a2 2 0 00-2-2h-3m-3-4H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v4a2 2 0 01-2 2h-5l-2 2-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun agent pour le moment</h3>
                        <p class="mt-1 text-sm text-gray-500">La liste des agents est actuellement vide.</p>
                    </div>
                </div>
            </template>
            <template x-for="agent in filteredAndSortedAgents" :key="agent.id">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300 transform hover:scale-105">
                    <div class="flex items-center space-x-4">
                        <div>
                            <template x-if="agent.image">
                                <img :src="'{{ asset('images') }}/' + agent.image" alt="Photo profil" class="h-14 w-14 rounded-full object-cover">
                            </template>
                            <template x-if="!agent.image">
                                <div class="h-14 w-14 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-xl">💼</span>
                                </div>
                            </template>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900" x-text="agent.nom + ' ' + agent.prenom"></h3>
                            <p class="text-sm text-gray-600" x-text="agent.email"></p>
                            <span class="mt-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 px-2">
                                Agent
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button @click="openDetailsModal(agent.id)" class="text-indigo-600 hover:text-indigo-900">Détails</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $agents->links() }}
        </div>
    </div>

    <!-- Details Modal -->
    <div x-show="isDetailsModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-show="isDetailsModalOpen" @click.away="isDetailsModalOpen = false">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Détails de l'Agent</h3>
                    <div class="flex items-center space-x-4 mb-4">
                        <div>
                            <template x-if="currentAgent.image">
                                <img :src="'{{ asset('images') }}/' + currentAgent.image" alt="Photo profil" class="h-20 w-20 rounded-full object-cover">
                            </template>
                            <template x-if="!currentAgent.image">
                                <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-2xl">💼</span>
                                </div>
                            </template>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800" x-text="currentAgent.nom + ' ' + currentAgent.prenom"></h4>
                            <p class="text-sm text-gray-500" x-text="currentAgent.email"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Téléphone:</span>
                            <span class="text-sm text-gray-500" x-text="currentAgent.num_tlph || 'Non spécifié'"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Adresse:</span>
                            <span class="text-sm text-gray-500" x-text="currentAgent.adresse || 'Non spécifié'"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Date de création:</span>
                            <span class="text-sm text-gray-500" x-text="currentAgent.created_at ? new Date(currentAgent.created_at).toLocaleString('fr-FR') : 'Non spécifié'"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Rôle:</span>
                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 px-2">
                                Agent
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="isDetailsModalOpen = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('agentList', () => ({
        searchQuery: '',
        isDetailsModalOpen: false,
        sortKey: 'nom',
        sortDirection: 'asc',
        currentAgent: {
            nom: '',
            prenom: '',
            email: '',
            num_tlph: '',
            adresse: '',
            role: 'agent',
            image: '',
            created_at: ''
        },
        agents: @json($agents->items()),

        get filteredAgents() {
            if (!this.searchQuery) return this.agents;
            const query = this.searchQuery.toLowerCase();
            return this.agents.filter(agent => {
                const fieldValue = (agent[this.sortKey] || '').toString().toLowerCase();
                return fieldValue.includes(query);
            });
        },

        get filteredAndSortedAgents() {
            const filtered = this.filteredAgents;
            return filtered.sort((a, b) => {
                const valA = (a[this.sortKey] || '').toString().toLowerCase();
                const valB = (b[this.sortKey] || '').toString().toLowerCase();
                if (this.sortDirection === 'asc') {
                    return valA > valB ? 1 : -1;
                } else {
                    return valA < valB ? 1 : -1;
                }
            });
        },

        sortBy(key) {
            if (this.sortKey === key) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortDirection = 'asc';
                this.searchQuery = ''; // Reset search when changing sort key
            }
        },

        openDetailsModal(agentId) {
            const agent = this.agents.find(u => u.id === agentId);
            if (agent) {
                this.currentAgent = {
                    nom: agent.nom || '',
                    prenom: agent.prenom || '',
                    email: agent.email || '',
                    num_tlph: agent.num_tlph || '',
                    adresse: agent.adresse || '',
                    role: agent.role || 'agent',
                    image: agent.image || '',
                    created_at: agent.created_at || ''
                };
                this.isDetailsModalOpen = true;
            } else {
                console.error('Agent not found for ID:', agentId);
            }
        }
    }));
});
</script>
@endsection