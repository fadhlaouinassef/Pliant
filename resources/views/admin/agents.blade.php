@extends('admin.dashboard')

@section('title', 'Liste des Agents')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="agentList()">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-12">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-white mb-2">Gestion des Agents</h1>
                            <p class="text-indigo-100 text-lg">Gérez et supervisez votre équipe d'agents</p>
                        </div>
                        <div class="hidden md:flex items-center space-x-6">
                            <div class="text-center bg-white/10 backdrop-blur rounded-xl p-4">
                                <div class="text-2xl font-bold text-white" x-text="agents.length"></div>
                                <div class="text-indigo-100 text-sm">Total Agents</div>
                            </div>
                            <div class="text-center bg-white/10 backdrop-blur rounded-xl p-4">
                                <div class="text-2xl font-bold text-white" x-text="filteredAndSortedAgents.length"></div>
                                <div class="text-indigo-100 text-sm">Affichés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        
                        <!-- Search Bar -->
                        <div class="flex-1 lg:max-w-md">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       x-model="searchQuery" 
                                       :placeholder="'Rechercher par ' + (sortKey === 'nom' ? 'nom' : 'email') + '...'" 
                                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            </div>
                        </div>

                        <!-- Sort Buttons -->
                        <div class="flex space-x-3">
                            <button @click="sortBy('nom')" 
                                    :class="{'bg-indigo-100 text-indigo-700 ring-2 ring-indigo-500': sortKey === 'nom'}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Nom
                                <svg x-show="sortKey === 'nom'" :class="{'rotate-180': sortDirection === 'desc'}" class="w-4 h-4 ml-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button @click="sortBy('email')" 
                                    :class="{'bg-indigo-100 text-indigo-700 ring-2 ring-indigo-500': sortKey === 'email'}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                Email
                                <svg x-show="sortKey === 'email'" :class="{'rotate-180': sortDirection === 'desc'}" class="w-4 h-4 ml-2 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <template x-if="filteredAndSortedAgents.length === 0">
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-3-3h-1m-4-5V8a4 4 0 10-8 0v2m6 6h4m-4 0a3 3 0 11-6 0m6 0v4m-6-4v4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun agent trouvé</h3>
                        <p class="text-gray-500 text-lg">
                            <span x-show="searchQuery">Aucun agent ne correspond à votre recherche "<span x-text="searchQuery" class="font-semibold"></span>"</span>
                            <span x-show="!searchQuery">La liste des agents est actuellement vide.</span>
                        </p>
                        <button x-show="searchQuery" @click="searchQuery = ''" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Effacer la recherche
                        </button>
                    </div>
                </div>
            </template>
            
            <template x-for="agent in filteredAndSortedAgents" :key="agent.id">
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <!-- Agent Card Header -->
                    <div class="relative bg-gradient-to-br from-indigo-50 to-purple-50 px-6 pt-6 pb-4">
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                </svg>
                                Agent
                            </span>
                        </div>
                        
                        <!-- Avatar -->
                        <div class="flex justify-center">
                            <div class="relative">
                                <template x-if="agent.image">
                                    <img :src="'{{ asset('images') }}/' + agent.image" 
                                         alt="Photo profil" 
                                         class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                </template>
                                <template x-if="!agent.image">
                                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center border-4 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <span class="text-2xl font-bold text-white" x-text="(agent.nom.charAt(0) + agent.prenom.charAt(0)).toUpperCase()"></span>
                                    </div>
                                </template>
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Card Body -->
                    <div class="px-6 py-4">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors" x-text="agent.nom + ' ' + agent.prenom"></h3>
                            <p class="text-gray-600 text-sm" x-text="agent.email"></p>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600" x-show="agent.num_tlph">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <span x-text="agent.num_tlph"></span>
                            </div>
                            <div class="flex items-start text-sm text-gray-600" x-show="agent.adresse">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="agent.adresse" class="line-clamp-2"></span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-gray-900">-</div>
                                <div class="text-xs text-gray-500">Réclamations</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-gray-900" x-text="agent.created_at ? Math.floor((new Date() - new Date(agent.created_at)) / (1000 * 60 * 60 * 24)) : '-'"></div>
                                <div class="text-xs text-gray-500">Jours</div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button @click="openDetailsModal(agent.id)" 
                                class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Voir les détails
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            {{ $agents->links() }}
        </div>

        <!-- Enhanced Details Modal -->
        <div x-show="isDetailsModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" @click="closeModal()"></div>
            
            <!-- Modal container -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Modal panel -->
                <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                
                <!-- Modal Header -->
                <div class="relative bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-8">
                    <div class="absolute top-4 right-4">
                        <button @click="closeModal()" 
                                class="text-white/80 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Agent Avatar -->
                        <div class="relative">
                            <template x-if="currentAgent.image">
                                <img :src="'{{ asset('images') }}/' + currentAgent.image" 
                                     alt="Photo profil" 
                                     class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg">
                            </template>
                            <template x-if="!currentAgent.image">
                                <div class="h-24 w-24 rounded-full bg-white/20 backdrop-blur border-4 border-white shadow-lg flex items-center justify-center">
                                    <span class="text-3xl font-bold text-white" x-text="(currentAgent.nom.charAt(0) + currentAgent.prenom.charAt(0)).toUpperCase()"></span>
                                </div>
                            </template>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-3 border-white rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Agent Info -->
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-white mb-1" x-text="currentAgent.nom + ' ' + currentAgent.prenom"></h2>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                    </svg>
                                    Agent
                                </span>
                                <span class="text-indigo-100 text-sm" x-text="currentAgent.email"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-8 py-6">
                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Informations de Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Email</span>
                                </div>
                                <p class="text-gray-900 font-medium" x-text="currentAgent.email"></p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Téléphone</span>
                                </div>
                                <p class="text-gray-900 font-medium" x-text="currentAgent.num_tlph || 'Non spécifié'"></p>
                            </div>
                        </div>
                        <div class="mt-4 bg-gray-50 rounded-xl p-4" x-show="currentAgent.adresse">
                            <div class="flex items-start mb-2">
                                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Adresse</span>
                            </div>
                            <p class="text-gray-900 font-medium" x-text="currentAgent.adresse"></p>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                            </svg>
                            Statistiques
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">-</div>
                                <div class="text-xs text-blue-600 font-medium">Réclamations traitées</div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-green-600">-</div>
                                <div class="text-xs text-green-600 font-medium">Réclamations résolues</div>
                            </div>
                            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-yellow-600">-</div>
                                <div class="text-xs text-yellow-600 font-medium">En cours</div>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-purple-600" x-text="currentAgent.created_at ? Math.floor((new Date() - new Date(currentAgent.created_at)) / (1000 * 60 * 60 * 24)) : '-'"></div>
                                <div class="text-xs text-purple-600 font-medium">Jours d'activité</div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Details -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Détails du Compte
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Date de création du compte</span>
                                    <p class="text-gray-900 font-medium" x-text="currentAgent.created_at ? new Date(currentAgent.created_at).toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Non spécifié'"></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-gray-700">Statut</span>
                                    <div class="flex items-center justify-end mt-1">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-green-600 font-medium text-sm">Actif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-8 py-4 flex justify-between items-center rounded-b-3xl">
                    <div class="flex space-x-3">
                        <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Contacter
                        </button>
                        <button class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Voir les réclamations
                        </button>
                    </div>
                    <button @click="closeModal()" 
                            class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function agentList() {
    return {
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
            console.log('Agent list initialized with', this.agents.length, 'agents');
            console.log('Alpine.js version:', window.Alpine?.version || 'Alpine not found');
            
            // Test Alpine reactivity
            setTimeout(() => {
                console.log('Testing modal state:', this.isDetailsModalOpen);
            }, 1000);
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
            
            const agent = this.agents.find(u => u.id == agentId);
            
            if (agent) {
                console.log('Found agent:', agent);
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
                console.log('Modal opened - State:', this.isDetailsModalOpen);
                
                // Force DOM update as fallback
                this.$nextTick(() => {
                    const modalEl = document.querySelector('[x-show="isDetailsModalOpen"]');
                    console.log('Modal element after state change:', modalEl);
                    if (modalEl) {
                        console.log('Modal computed display:', window.getComputedStyle(modalEl).display);
                        // Force show if Alpine fails
                        if (window.getComputedStyle(modalEl).display === 'none') {
                            console.log('Forcing modal display');
                            modalEl.style.display = 'block';
                        }
                    }
                });
            } else {
                console.error('Agent not found for ID:', agentId);
            }
        },

        closeModal() {
            this.isDetailsModalOpen = false;
            console.log('Modal closed');
        }
    }
}
</script>

<style>
/* Custom animations and effects */
[x-cloak] { 
    display: none !important; 
}

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

@keyframes pulse-ring {
    0% {
        transform: scale(0.33);
    }
    40%, 50% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: scale(1.33);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.pulse-ring::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border: 2px solid #10b981;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for modal */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #6366f1, #8b5cf6);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #4f46e5, #7c3aed);
}

/* Hover effects */
.card-hover-effect {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover-effect:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Glass morphism effect */
.glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Button hover effects */
.btn-hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endsection