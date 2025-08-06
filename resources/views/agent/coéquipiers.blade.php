@extends('agent.dashboard')

@section('title', 'Liste des Coéquipiers')

@section('content')
<div class="w-full mx-auto" x-data="agentList">
    <!-- Modern Header with Stats -->
    <div class="relative bg-gradient-to-r from-teal-600 via-teal-700 to-teal-800 rounded-2xl shadow-xl mb-8 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Mes Coéquipiers</h1>
                    <p class="text-teal-100 text-lg">Gérez et collaborez avec votre équipe</p>
                </div>
                <div class="mt-4 md:mt-0 grid grid-cols-2 gap-4">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-white" x-text="agents.length"></div>
                        <div class="text-teal-100 text-sm">Total Agents</div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-white" x-text="filteredAndSortedAgents.length"></div>
                        <div class="text-teal-100 text-sm">Affichés</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Control Panel -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center">
            <!-- Search Bar -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    :placeholder="'Rechercher par ' + (sortKey === 'nom' ? 'nom' : 'email') + '...'" 
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                >
            </div>
            
            <!-- Sort Buttons -->
            <div class="flex space-x-3">
                <!-- Test button for debugging -->
                <button 
                    @click="console.log('Test button clicked'); isDetailsModalOpen = true" 
                    class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm"
                >
                    Test Modal
                </button>
                
                <button 
                    @click="sortBy('nom')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center space-x-2"
                    :class="sortKey === 'nom' 
                        ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-lg transform scale-105' 
                        : 'bg-gray-50 text-gray-600 hover:bg-teal-50 hover:text-teal-600 border border-gray-200'"
                >
                    <span>Nom</span>
                    <svg x-show="sortKey === 'nom'" class="w-4 h-4" :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
                <button 
                    @click="sortBy('email')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center space-x-2"
                    :class="sortKey === 'email' 
                        ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-lg transform scale-105' 
                        : 'bg-gray-50 text-gray-600 hover:bg-teal-50 hover:text-teal-600 border border-gray-200'"
                >
                    <span>Email</span>
                    <svg x-show="sortKey === 'email'" class="w-4 h-4" :class="sortDirection === 'asc' ? 'rotate-0' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Modern Agents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <template x-if="filteredAndSortedAgents.length === 0">
            <div class="col-span-full">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-12 text-center border border-gray-200">
                    <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-4m-1-7a3 3 0 11-6 0m6 0a3 3 0 10-6 0m6 0v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v1.02"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Aucun coéquipier trouvé</h3>
                    <p class="text-gray-500 max-w-md mx-auto">La recherche ne correspond à aucun agent ou la liste est actuellement vide.</p>
                </div>
            </div>
        </template>
        
        <template x-for="agent in filteredAndSortedAgents" :key="agent.id">
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <!-- Gradient Overlay -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600"></div>
                
                <div class="p-6">
                    <!-- Agent Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="relative">
                            <template x-if="agent.image">
                                <img :src="'{{ asset('images') }}/' + agent.image" alt="Photo profil" class="w-16 h-16 rounded-full object-cover ring-4 ring-teal-100 group-hover:ring-teal-200 transition-all duration-300">
                            </template>
                            <template x-if="!agent.image">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center ring-4 ring-teal-100 group-hover:ring-teal-200 transition-all duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </template>
                            <!-- Online Status Indicator -->
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 truncate group-hover:text-teal-700 transition-colors duration-200" x-text="agent.nom + ' ' + agent.prenom"></h3>
                            <p class="text-sm text-gray-500 truncate flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span x-text="agent.email"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Agent Info -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-600" x-text="agent.num_tlph || 'Non spécifié'"></span>
                        </div>
                        <div class="flex items-start text-sm">
                            <svg class="w-4 h-4 text-teal-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-600 line-clamp-2" x-text="agent.adresse || 'Non spécifié'"></span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-teal-100 to-teal-200 text-teal-800 border border-teal-300">
                            <div class="w-2 h-2 bg-teal-500 rounded-full mr-2 animate-pulse"></div>
                            Agent Actif
                        </span>
                        <div class="text-xs text-gray-400" x-text="agent.created_at ? 'Depuis ' + new Date(agent.created_at).toLocaleDateString('fr-FR') : ''"></div>
                    </div>

                    <!-- Action Button -->
                    <button 
                        @click="openDetailsModal(agent.id)" 
                        class="w-full bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform group-hover:scale-105 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Voir les détails</span>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        <div class="bg-white rounded-2xl shadow-lg p-4 border border-gray-100">
            {{ $agents->links() }}
        </div>
    </div>

    <!-- Enhanced Details Modal -->
    <div x-show="isDetailsModalOpen" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-black bg-opacity-50 backdrop-blur-sm" aria-hidden="true" @click="isDetailsModalOpen = false"></div>
            
            <!-- Modal container -->
            <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-gray-100"
                 x-show="isDetailsModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 -m-6 mb-6 p-6 rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-white">Profil du Coéquipier</h3>
                        <button @click="isDetailsModalOpen = false" class="text-white hover:text-teal-200 transition-colors duration-200 p-2 hover:bg-white hover:bg-opacity-20 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Agent Profile Header -->
                <div class="flex items-center space-x-6 mb-8 p-6 bg-gradient-to-r from-teal-50 to-teal-100 rounded-2xl border border-teal-200">
                    <div class="relative">
                        <template x-if="currentAgent.image">
                            <img :src="'{{ asset('images') }}/' + currentAgent.image" alt="Photo profil" class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-lg">
                        </template>
                        <template x-if="!currentAgent.image">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center ring-4 ring-white shadow-lg">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </template>
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-400 rounded-full border-3 border-white animate-pulse"></div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-2xl font-bold text-gray-800 mb-1" x-text="currentAgent.nom + ' ' + currentAgent.prenom"></h4>
                        <p class="text-teal-600 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span x-text="currentAgent.email"></span>
                        </p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800 border border-teal-300 mt-2">
                            <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                            Agent Coéquipier
                        </span>
                    </div>
                </div>

                <!-- Contact Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <h5 class="font-semibold text-gray-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Téléphone
                        </h5>
                        <p class="text-gray-600 font-medium" x-text="currentAgent.num_tlph || 'Non spécifié'"></p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <h5 class="font-semibold text-gray-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Adresse
                        </h5>
                        <p class="text-gray-600" x-text="currentAgent.adresse || 'Non spécifié'"></p>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl border border-teal-200">
                        <div class="text-2xl font-bold text-teal-600">✓</div>
                        <div class="text-sm text-teal-700 font-medium">Actif</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600" x-text="new Date(currentAgent.created_at).getFullYear() || 'N/A'"></div>
                        <div class="text-sm text-blue-700 font-medium">Depuis</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-2xl font-bold text-green-600">👥</div>
                        <div class="text-sm text-green-700 font-medium">Équipe</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="button" @click="isDetailsModalOpen = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Fermer</span>
                    </button>
                    <button type="button" class="px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Contacter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .7;
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom backdrop blur for better browser support */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Enhanced glassmorphism effect */
.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Smooth transitions for all interactive elements */
* {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #0f766e;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #0d5554;
}
</style>

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

        init() {
            console.log('Alpine component initialized');
            console.log('Agents loaded:', this.agents);
            console.log('Number of agents:', this.agents.length);
        },

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
            console.log('Opening modal for agent ID:', agentId);
            const agent = this.agents.find(u => u.id === agentId);
            console.log('Found agent:', agent);
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
                console.log('Modal should be open now, isDetailsModalOpen:', this.isDetailsModalOpen);
            } else {
                console.error('Agent not found for ID:', agentId);
            }
        }
    }));
});
</script>
@endsection