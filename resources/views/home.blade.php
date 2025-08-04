@extends('app')

@section('title', 'Pliant - Gestion de Réclamations')

@section('content')
    <!-- Adding Alpine.js, Three.js and Font Awesome via CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Alpine.js feedback form data -->
    <div x-data="feedbackForm()" class="relative">
        <script>
            function feedbackForm() {
                return {
                    rating: 0,
                    isSubmitting: false,
                    showToast: false,
                    toastMessage: '',
                    toastType: 'success',
                    
                    setRating(value) {
                        this.rating = value;
                    },
                    
                    closeForm() {
                        document.getElementById('feedback-form').style.display = 'none';
                        this.resetForm();
                    },
                    
                    resetForm() {
                        this.rating = 0;
                        document.getElementById('user-name').value = '';
                        document.getElementById('user-comment').value = '';
                    },
                    
                    async submitForm() {
                        if (this.rating === 0) {
                            this.showToastMessage('Veuillez sélectionner une note', 'error');
                            return;
                        }
                        
                        this.isSubmitting = true;
                        
                        try {
                            const formData = new FormData(document.getElementById('avis-form'));
                            const response = await fetch(document.getElementById('avis-form').action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            if (response.ok) {
                                this.showToastMessage('Votre avis a été envoyé avec succès!', 'success');
                                setTimeout(() => {
                                    this.closeForm();
                                }, 2000);
                            } else {
                                throw new Error('Erreur lors de l\'envoi');
                            }
                        } catch (error) {
                            console.error('Erreur:', error);
                            this.showToastMessage('Une erreur est survenue. Veuillez réessayer.', 'error');
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    
                    showToastMessage(message, type) {
                        this.toastMessage = message;
                        this.toastType = type;
                        this.showToast = true;
                        setTimeout(() => {
                            this.showToast = false;
                        }, 3000);
                    }
                };
            }
        </script>
        
        <!-- Toast Notification -->
        <div x-show="showToast" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            @click="showToast = false"
            class="fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50" 
            :class="toastType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
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
                <span x-text="toastMessage"></span>
            </div>
        </div>

        <!-- Feedback Button -->
        <button id="open-feedback-btn" class="fixed bottom-6 right-6 z-40">
            <i class="fas fa-comment-alt"></i>
            <span>Feedback</span>
        </button>

        <!-- Feedback Dialog -->
        <div id="feedback-form" class="fixed bottom-16 right-16 z-50 hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-5 flex justify-between items-center">
                    <h3 class="text-xl font-kollektif">Votre Avis Compte</h3>
                    <button id="close-feedback-btn" class="close-btn text-white hover:text-red-400" @click="closeForm()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('avis.store') }}" method="post" class="p-6 space-y-5" id="avis-form" @submit.prevent="submitForm">
                    @csrf
                    <div>
                        <label for="user-name" class="block text-sm font-medium text-gray-700 mb-1">Nom Utilisateur</label>
                        <input type="text" id="user-name" name="nom_utilisateur" required 
                               class="form-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label for="user-comment" class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                        <textarea id="user-comment" name="commentaire" rows="4" required
                                  class="form-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <div class="flex space-x-2">
                            <template x-for="i in 5" :key="i">
                                <i class="fas fa-star text-xl cursor-pointer transition"
                                   :class="rating >= i ? 'text-yellow-400' : 'text-gray-300'"
                                   @click="setRating(i)"></i>
                            </template>
                        </div>
                        <input type="hidden" id="user-rating" name="note" x-model="rating">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-feedback-btn" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition font-medium" @click="closeForm()">Annuler</button>
                        <button type="submit" id="submit-avis-btn" class="submit-btn bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium flex items-center" :disabled="isSubmitting">
                            <span x-text="isSubmitting ? 'Envoi en cours...' : 'Envoyer'"></span>
                            <span x-show="isSubmitting" class="ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Styles for Feedback Button and Dialog -->
    <style>
        #feedback-form {
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #open-feedback-btn {
            position: fixed;
            bottom: 24px;
            right: 16px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border: none;
            border-radius: 50%;
            padding: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 40;
        }

        #open-feedback-btn:hover {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        #open-feedback-btn i {
            font-size: 24px;
        }

        #open-feedback-btn span {
            display: none;
            position: absolute;
            right: 60px;
            background: #1e40af;
            color: white;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #open-feedback-btn:hover span {
            display: block;
            opacity: 1;
        }

        #feedback-form .close-btn {
            transition: transform 0.2s ease, color 0.2s ease;
        }

        #feedback-form .close-btn:hover {
            transform: rotate(90deg);
            color: #dc2626;
        }

        #feedback-form .submit-btn:hover {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            transform: translateY(-1px);
        }

        #feedback-form .form-field {
            transition: all 0.2s ease;
        }

        #feedback-form .form-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Alpine.js x-cloak directive */
        [x-cloak] {
            display: none !important;
        }
        
        /* Guide Modal Styles - Compact Version */
        #guideModal {
            backdrop-filter: blur(4px);
        }
        
        #highlightBox {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.4);
            border-radius: 8px;
        }
        
        #guideTypewriter {
            font-family: 'Courier New', monospace;
            border-right: 2px solid #3b82f6;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 50% { border-color: #3b82f6; }
            51%, 100% { border-color: transparent; }
        }
        
        /* Blur everything except highlighted elements */
        #guideModal.active ~ * {
            filter: blur(2px);
            transition: filter 0.3s ease;
        }
        
        /* Ensure highlighted elements stay sharp */
        [style*="z-index: 60"] {
            filter: none !important;
        }
    </style>

    <!-- Guide Modal -->
    <div id="guideModal" class="fixed inset-0 z-50 hidden">
        <!-- Blur Background -->
        <div class="absolute inset-0 backdrop-blur-sm bg-black bg-opacity-40"></div>
        
        <!-- Guide Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
                <!-- Close Button -->
                <button id="closeGuide" class="absolute top-4 right-4 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-all duration-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-rocket text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-kollektif text-gray-900 mb-2">Bienvenue sur Pliant</h2>
                    <p class="text-gray-600 text-sm">Découvrez comment utiliser la plateforme</p>
                </div>
                
                <!-- Typewriter Text -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg mb-6">
                    <p id="guideTypewriter" class="text-gray-800 text-center font-medium leading-relaxed min-h-[60px] flex items-center justify-center"></p>
                </div>
                
                <!-- Navigation -->
                <div class="flex justify-between items-center">
                    <button id="prevGuide" class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="text-sm">Précédent</span>
                    </button>
                    
                    <!-- Step Indicators -->
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500" data-step="0"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300" data-step="1"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300" data-step="2"></div>
                        <div class="w-2 h-2 rounded-full bg-gray-300" data-step="3"></div>
                    </div>
                    
                    <button id="nextGuide" class="flex items-center space-x-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">
                        <span class="text-sm">Suivant</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Highlight for specific elements -->
        <div id="highlightBox" class="absolute border-4 border-blue-500 rounded-lg shadow-lg pointer-events-none opacity-0 transition-all duration-500"></div>
    </div>

    <!-- Rest of the Page Content -->
    <section class="relative bg-gradient-to-r from-blue-900 to-blue-700 h-screen flex items-center justify-center text-white overflow-hidden">
        <canvas id="particleCanvasHero" class="absolute inset-0"></canvas>
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-4 z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-kollektif mb-6 leading-tight">Simplifiez la Gestion de vos Réclamations</h1>
                <p id="typewriter-text" class="text-xl md:text-2xl mb-8 text-blue-100"></p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#demo" class="inline-block bg-white text-blue-900 font-kollektif py-4 px-8 rounded-full text-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">Demander une démo</a>
                    <a href="#features" class="inline-block bg-transparent border-2 border-white text-white font-kollektif py-4 px-8 rounded-full text-lg transition duration-300 transform hover:scale-105 hover:bg-white hover:text-blue-900">Découvrir les fonctionnalités</a>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-kollektif text-center mb-16 text-blue-900">Fonctionnalités Principales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-tasks text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-kollektif mb-4 text-blue-900">Suivi en Temps Réel</h3>
                    <p class="text-gray-600 leading-relaxed">Surveillez l'état de vos réclamations en temps réel et prenez des décisions éclairées rapidement.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-kollektif mb-4 text-blue-900">Analyses Avancées</h3>
                    <p class="text-gray-600 leading-relaxed">Obtenez des insights précieux grâce à nos outils d'analyse et tableaux de bord personnalisables.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-kollektif mb-4 text-blue-900">Collaboration d'Équipe</h3>
                    <p class="text-gray-600 leading-relaxed">Facilitez la communication et la collaboration entre les équipes pour une résolution efficace.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-20 bg-blue-900 text-white overflow-hidden">
        <canvas id="particleCanvasCTA" class="absolute inset-0"></canvas>
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-4 z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-kollektif mb-8">Prêt à Transformer votre Gestion des Réclamations ?</h2>
                <p class="text-xl text-blue-100 mb-12">Rejoignez les entreprises qui font confiance à Pliant pour optimiser leur processus.</p>
                <a href="#contact" class="inline-block bg-white text-blue-900 font-kollektif py-4 px-12 rounded-full text-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">Commencer Maintenant</a>
            </div>
        </div>
    </section>

    <section>
        <div class="text-gray-900 pt-16 pb-24 px-6 w-full bg-gray-50">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6 text-gray-800">Our Impact</h2>
                <p class="text-lg text-gray-600 mb-16">Join thousands who trust our platform</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- User Count -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                        <div class="mb-6 flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-pink-400 p-1">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-12 h-12 text-gray-900" viewBox="0 0 24 24">
                                    <path d="M12 5.5A3.5 3.5 0 0 1 15.5 9a3.5 3.5 0 0 1-3.5 3.5A3.5 3.5 0 0 1 8.5 9A3.5 3.5 0 0 1 12 5.5M5 8c.56 0 1.08.15 1.53.42c-.15 1.43.27 2.85 1.13 3.96C7.16 13.34 6.16 14 5 14a3 3 0 0 1-3-3a3 3 0 0 1 3-3m14 0a3 3 0 0 1 3 3a3 3 0 0 1-3 3c-1.16 0-2.16-.66-2.66-1.62a5.54 5.54 0 0 0 1.13-3.96c.45-.27.97-.42 1.53-.42M5.5 18.25c0-2.07 2.91-3.75 6.5-3.75s6.5 1.68 6.5 3.75V20h-13zM0 20v-1.5c0-1.39 1.89-2.56 4.45-2.9c-.59.68-.95 1.62-.95 2.65V20zm24 0h-3.5v-1.75c0-1.03-.36-1.97-.95-2.65c2.56.34 4.45 1.51 4.45 2.9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-800">70,680 +</div>
                        <div class="text-gray-500">Users</div>
                    </div>
                    <!-- Documents Count -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                        <div class="mb-6 flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-pink-400 p-1">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-12 h-12 text-gray-900" viewBox="0 0 24 24">
                                    <path d="M6 2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm0 2h7v5h5v11H6zm2 8v2h8v-2zm0 4v2h5v-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-800">651,589 +</div>
                        <div class="text-gray-500">Papers and Documents Processed</div>
                    </div>
                    <!-- Languages Supported -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                        <div class="mb-6 flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-pink-400 p-1">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-12 h-12 text-gray-900" viewBox="0 0 24 24">
                                    <path d="M17.9 17.39c-.26-.8-1.01-1.39-1.9-1.39h-1v-3a1 1 0 0 0-1-1H8v-2h2a1 1 0 0 0 1-1V7h2a2 2 0 0 0 2-2v-.41a7.984 7.984 0 0 1 2.9 12.8M11 19.93c-3.95-.49-7-3.85-7-7.93c0-.62.08-1.22.21-1.79L9 15v1a2 2 0 0 0 2 2m1-16A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-extrabold text-gray-800">50 +</div>
                        <div class="text-gray-500">Languages Supported</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-10">Ce que nos utilisateurs pensent</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center">
                @php
                    // Récupérer les avis visibles et les mélanger aléatoirement
                    $avis = \App\Models\Avis::where('etat', 'visible')->inRandomOrder()->take(3)->get();
                @endphp
                
                @forelse($avis as $a)
                <!-- Testimonial Card -->
                <div class="bg-white shadow-md rounded-2xl p-6 w-full max-w-md flex flex-col items-start hover:shadow-xl transition">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($a->nom_utilisateur, 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $a->nom_utilisateur }}</h4>
                            <div class="flex mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $a->note)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="fas fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"{{ $a->commentaire }}"</p>
                    <p class="text-gray-400 text-xs mt-3">{{ $a->created_at->format('d/m/Y') }}</p>
                </div>
                @empty
                <!-- Testimonial Card par défaut si aucun avis -->
                <div class="bg-white shadow-md rounded-2xl p-6 w-full max-w-md flex flex-col items-start hover:shadow-xl transition">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            P
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Premier Avis</h4>
                            <p class="text-sm text-gray-500">Soyez le premier à donner votre avis!</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">Cliquez sur le bouton "Feedback" en bas à droite pour partager votre expérience avec nous.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- JavaScript for Modal Navigation, Feedback Dialog, and Three.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Guide Modal Logic with Blur and Highlighting
            const guideModal = document.getElementById('guideModal');
            const closeGuide = document.getElementById('closeGuide');
            const prevGuide = document.getElementById('prevGuide');
            const nextGuide = document.getElementById('nextGuide');
            const guideTypewriter = document.getElementById('guideTypewriter');
            const highlightBox = document.getElementById('highlightBox');
            const stepIndicators = document.querySelectorAll('[data-step]');
            
            let currentStep = 0;
            let typewriterTimeout;
            
            // Guide steps with messages and elements to highlight
            const guideSteps = [
                {
                    message: "Commencez par vous connecter pour accéder à votre tableau de bord personnalisé",
                    selector: "a[href*='login'], .login-btn, [href*='connexion']",
                    position: "bottom"
                },
                {
                    message: "Créez une nouvelle réclamation en cliquant sur le bouton 'Nouvelle Réclamation'",
                    selector: ".btn-primary, [href*='reclamation'], .create-btn",
                    position: "top"
                },
                {
                    message: "Suivez l'état de vos réclamations dans le tableau de bord",
                    selector: ".dashboard, .reclamations-list, [href*='dashboard']",
                    position: "left"
                },
                {
                    message: "Consultez les statistiques et analyses pour optimiser vos processus",
                    selector: ".stats, .analytics, [href*='statistiques']",
                    position: "right"
                }
            ];

            // Show guide modal after 3 seconds
            setTimeout(() => {
                showGuide();
            }, 3000);

            function showGuide() {
                guideModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                showStep(0);
            }

            function hideGuide() {
                guideModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                clearTimeout(typewriterTimeout);
                hideHighlight();
            }

            function showStep(stepIndex) {
                if (stepIndex < 0 || stepIndex >= guideSteps.length) return;
                
                currentStep = stepIndex;
                const step = guideSteps[stepIndex];
                
                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    if (index === stepIndex) {
                        indicator.classList.remove('bg-gray-300');
                        indicator.classList.add('bg-blue-500');
                    } else {
                        indicator.classList.remove('bg-blue-500');
                        indicator.classList.add('bg-gray-300');
                    }
                });
                
                // Update navigation buttons
                prevGuide.disabled = stepIndex === 0;
                if (stepIndex === guideSteps.length - 1) {
                    nextGuide.innerHTML = `
                        <span class="text-sm">Terminer</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    `;
                } else {
                    nextGuide.innerHTML = `
                        <span class="text-sm">Suivant</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    `;
                }
                
                // Show typewriter text
                typewriterEffect(step.message);
                
                // Highlight element
                highlightElement(step.selector);
            }

            function typewriterEffect(text) {
                guideTypewriter.textContent = '';
                clearTimeout(typewriterTimeout);
                
                let i = 0;
                function typeChar() {
                    if (i < text.length) {
                        guideTypewriter.textContent += text.charAt(i);
                        i++;
                        typewriterTimeout = setTimeout(typeChar, 50);
                    }
                }
                typeChar();
            }

            function highlightElement(selector) {
                hideHighlight();
                
                // Try to find the element
                const element = document.querySelector(selector);
                if (!element) {
                    // If element not found, try alternative selectors
                    const alternatives = [
                        "a[href*='login']",
                        ".btn-primary", 
                        "a[href*='connexion']",
                        "nav a",
                        ".hero button",
                        "header a"
                    ];
                    
                    for (let alt of alternatives) {
                        const altElement = document.querySelector(alt);
                        if (altElement) {
                            showHighlight(altElement);
                            return;
                        }
                    }
                    return;
                }
                
                showHighlight(element);
            }

            function showHighlight(element) {
                const rect = element.getBoundingClientRect();
                const scrollY = window.scrollY;
                const scrollX = window.scrollX;
                
                highlightBox.style.left = (rect.left + scrollX - 8) + 'px';
                highlightBox.style.top = (rect.top + scrollY - 8) + 'px';
                highlightBox.style.width = (rect.width + 16) + 'px';
                highlightBox.style.height = (rect.height + 16) + 'px';
                highlightBox.style.opacity = '1';
                
                // Remove blur from highlighted element
                element.style.position = 'relative';
                element.style.zIndex = '60';
                element.style.filter = 'none';
                
                // Add pulse animation
                highlightBox.style.boxShadow = '0 0 0 4px rgba(59, 130, 246, 0.4)';
                
                // Scroll to element if needed
                const elementTop = rect.top + scrollY - 100;
                if (elementTop < scrollY || elementTop > scrollY + window.innerHeight) {
                    window.scrollTo({
                        top: elementTop,
                        behavior: 'smooth'
                    });
                }
            }

            function hideHighlight() {
                highlightBox.style.opacity = '0';
                
                // Remove any special styling from previously highlighted elements
                document.querySelectorAll('[style*="z-index: 60"]').forEach(el => {
                    el.style.position = '';
                    el.style.zIndex = '';
                    el.style.filter = '';
                });
            }

            // Event listeners
            closeGuide.addEventListener('click', hideGuide);
            
            nextGuide.addEventListener('click', () => {
                if (currentStep < guideSteps.length - 1) {
                    showStep(currentStep + 1);
                } else {
                    hideGuide();
                }
            });

            prevGuide.addEventListener('click', () => {
                if (currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });

            // Clickable step indicators
            stepIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showStep(index);
                });
                indicator.style.cursor = 'pointer';
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!guideModal.classList.contains('hidden')) {
                    if (e.key === 'ArrowRight' && currentStep < guideSteps.length - 1) {
                        showStep(currentStep + 1);
                    } else if (e.key === 'ArrowLeft' && currentStep > 0) {
                        showStep(currentStep - 1);
                    } else if (e.key === 'Escape') {
                        hideGuide();
                    }
                }
            });

            // Close guide when clicking outside
            guideModal.addEventListener('click', (e) => {
                if (e.target === guideModal) {
                    hideGuide();
                }
            });

            // Feedback Dialog Logic
            const feedbackForm = document.getElementById('feedback-form');
            const openFeedbackBtn = document.getElementById('open-feedback-btn');
            const closeFeedbackBtn = document.getElementById('close-feedback-btn');
            const cancelFeedbackBtn = document.getElementById('cancel-feedback-btn');

            // Open feedback form
            openFeedbackBtn.addEventListener('click', () => {
                feedbackForm.style.display = 'block';
            });

            // Close feedback form
            function closeForm() {
                feedbackForm.style.display = 'none';
                resetFeedbackForm();
            }

            closeFeedbackBtn.addEventListener('click', closeForm);
            cancelFeedbackBtn.addEventListener('click', closeForm);

            // Reset feedback form
            function resetFeedbackForm() {
                document.getElementById('user-name').value = '';
                document.getElementById('user-comment').value = '';
            }

            // Three.js Particle System for Hero Section
            const canvasHero = document.getElementById('particleCanvasHero');
            const sceneHero = new THREE.Scene();
            const cameraHero = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const rendererHero = new THREE.WebGLRenderer({ canvas: canvasHero, alpha: true });
            rendererHero.setSize(window.innerWidth, window.innerHeight);
            cameraHero.position.z = 5;

            const particlesHero = new THREE.BufferGeometry();
            const particleCountHero = 1000;
            const positionsHero = new Float32Array(particleCountHero * 3);
            for (let i = 0; i < particleCountHero * 3; i++) {
                positionsHero[i] = (Math.random() - 0.5) * 10;
            }
            particlesHero.setAttribute('position', new THREE.BufferAttribute(positionsHero, 3));
            const materialHero = new THREE.PointsMaterial({ color: 0xffffff, size: 0.02 });
            const particleSystemHero = new THREE.Points(particlesHero, materialHero);
            sceneHero.add(particleSystemHero);

            function animateParticlesHero() {
                requestAnimationFrame(animateParticlesHero);
                particleSystemHero.rotation.y += 0.001;
                rendererHero.render(sceneHero, cameraHero);
            }
            animateParticlesHero();

            // Three.js Particle System for CTA Section
            const canvasCTA = document.getElementById('particleCanvasCTA');
            const sceneCTA = new THREE.Scene();
            const cameraCTA = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const rendererCTA = new THREE.WebGLRenderer({ canvas: canvasCTA, alpha: true });
            rendererCTA.setSize(window.innerWidth, window.innerHeight);
            cameraCTA.position.z = 5;

            const particlesCTA = new THREE.BufferGeometry();
            const particleCountCTA = 1000;
            const positionsCTA = new Float32Array(particleCountCTA * 3);
            for (let i = 0; i < particleCountCTA * 3; i++) {
                positionsCTA[i] = (Math.random() - 0.5) * 10;
            }
            particlesCTA.setAttribute('position', new THREE.BufferAttribute(positionsCTA, 3));
            const materialCTA = new THREE.PointsMaterial({ color: 0xffffff, size: 0.02 });
            const particleSystemCTA = new THREE.Points(particlesCTA, materialCTA);
            sceneCTA.add(particleSystemCTA);

            function animateParticlesCTA() {
                requestAnimationFrame(animateParticlesCTA);
                particleSystemCTA.rotation.y += 0.001;
                rendererCTA.render(sceneCTA, cameraCTA);
            }
            animateParticlesCTA();

            // Handle window resize
            window.addEventListener('resize', () => {
                cameraHero.aspect = window.innerWidth / window.innerHeight;
                cameraHero.updateProjectionMatrix();
                rendererHero.setSize(window.innerWidth, window.innerHeight);

                cameraCTA.aspect = window.innerWidth / window.innerHeight;
                cameraCTA.updateProjectionMatrix();
                rendererCTA.setSize(window.innerWidth, window.innerHeight);
            });

            // Typewriter Effect
            const typewriterText = document.getElementById('typewriter-text');
            const textToType = "Une solution professionnelle pour transformer vos défis en opportunités d'amélioration";
            let index = 0;

            function typeWriter() {
                typewriterText.textContent = '';
                index = 0;
                function type() {
                    if (index < textToType.length) {
                        typewriterText.textContent += textToType.charAt(index);
                        index++;
                        setTimeout(type, 100);
                    }
                }
                type();
            }

            setInterval(typeWriter, 60000);
            typeWriter();
        });
    </script>
@endsection
