@extends('app')

@section('title', 'Pliant - Gestion de Réclamations')

@section('content')
    <!-- Adding Three.js and Font Awesome via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Guide Modal -->
    <div id="guideModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-[90vw] w-full md:w-3/4 lg:w-1/2 max-h-[90vh] overflow-y-auto relative">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-kollektif text-blue-900">Guide Complet d'Utilisation</h2>
                    <button id="closeGuide" class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Table of Contents -->
                <div class="mb-8">
                    <h3 class="text-xl font-kollektif text-blue-800 mb-4">Table des Matières</h3>
                    <ul class="list-disc pl-5 space-y-2 text-blue-600">
                        <li><a href="#section1" class="hover:underline">Création de Réclamations</a></li>
                        <li><a href="#section2" class="hover:underline">Suivi des Réclamations</a></li>
                        <li><a href="#section3" class="hover:underline">Fonctionnalités Avancées</a></li>
                        <li><a href="#section4" class="hover:underline">Bonnes Pratiques</a></li>
                    </ul>
                </div>

                <div class="space-y-8">
                    <!-- Section 1 -->
                    <div id="section1" class="bg-blue-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-2xl font-kollektif text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-file-alt mr-3 text-blue-600"></i> Création de Réclamations
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Étape 1 : Accéder au formulaire</h4>
                                <p class="text-gray-700 text-base">Cliquez sur "Nouvelle Réclamation" dans le tableau de bord ou le menu.</p>
                                <img src="https://via.placeholder.com/600x400?text=Capture+Formulaire" alt="Formulaire annoté" class="mt-3 rounded-lg shadow-md w-full h-auto">
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Étape 2 : Remplir les informations</h4>
                                <ul class="list-disc pl-5 space-y-2 text-gray-700 text-base">
                                    <li>Titre clair et descriptif</li>
                                    <li>Description détaillée du problème</li>
                                    <li>Catégorie appropriée</li>
                                    <li>Pièces jointes si nécessaire</li>
                                </ul>
                                <details class="mt-4">
                                    <summary class="text-blue-600 cursor-pointer font-medium">Astuces pour une description efficace</summary>
                                    <div class="mt-2 p-4 bg-blue-100 rounded-lg text-gray-700">
                                        <p>- Soyez précis et concis</p>
                                        <p>- Ajoutez des exemples si possible</p>
                                        <p>- Mentionnez les impacts</p>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div id="section2" class="bg-blue-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-2xl font-kollektif text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-eye mr-3 text-blue-600"></i> Suivi des Réclamations
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Tableau de bord</h4>
                                <p class="text-gray-700 text-base">Visualisez toutes vos réclamations :</p>
                                <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700 text-base">
                                    <li>Réclamations en attente</li>
                                    <li>Réclamations en cours</li>
                                    <li>Réclamations résolues</li>
                                    <li>Délais de traitement</li>
                                </ul>
                                <img src="https://via.placeholder.com/600x400?text=Capture+Tableau+de+Bord" alt="Tableau de bord annoté" class="mt-3 rounded-lg shadow-md w-full h-auto">
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Notifications</h4>
                                <p class="text-gray-700 text-base">Restez informé en temps réel :</p>
                                <div class="mt-3 space-y-3">
                                    <div class="flex items-start bg-white p-3 rounded-lg shadow-sm">
                                        <div class="bg-green-100 p-2 rounded-full mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-700">Réclamation #1234 résolue</p>
                                            <p class="text-sm text-gray-500">Il y a 2 heures</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div id="section3" class="bg-blue-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-2xl font-kollektif text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-cogs mr-3 text-blue-600"></i> Fonctionnalités Avancées
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Analytiques et Rapports</h4>
                                <p class="text-gray-700 text-base">Analysez vos données :</p>
                                <div class="grid md:grid-cols-3 gap-4 mt-3">
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                                        <p class="font-medium text-blue-700">Temps moyen de résolution</p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                                        <p class="font-medium text-blue-700">Taux de satisfaction</p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                                        <p class="font-medium text-blue-700">Catégories fréquentes</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Collaboration d'Équipe</h4>
                                <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700 text-base">
                                    <li>Assignation de réclamations</li>
                                    <li>Commentaires internes</li>
                                    <li>Historique des modifications</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div id="section4" class="bg-blue-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-2xl font-kollektif text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-3 text-blue-600"></i> Bonnes Pratiques
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Pour des réponses rapides</h4>
                                <ul class="list-disc pl-5 space-y-2 text-gray-700 text-base">
                                    <li>Soyez précis dans votre description</li>
                                    <li>Fournissez toutes les informations</li>
                                    <li>Utilisez des captures d'écran</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2 text-blue-700">Évitez ces erreurs</h4>
                                <ul class="list-disc pl-5 space-y-2 text-gray-700 text-base">
                                    <li>Description vague</li>
                                    <li>Ne pas vérifier les FAQ</li>
                                    <li>Créer des doublons</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                    <button id="prevSection" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Précédent
                    </button>
                    <div class="flex space-x-2">
                        <div class="w-4 h-4 rounded-full bg-blue-600 cursor-pointer hover:bg-blue-400" data-section="0"></div>
                        <div class="w-4 h-4 rounded-full bg-blue-200 cursor-pointer hover:bg-blue-400" data-section="1"></div>
                        <div class="w-4 h-4 rounded-full bg-blue-200 cursor-pointer hover:bg-blue-400" data-section="2"></div>
                        <div class="w-4 h-4 rounded-full bg-blue-200 cursor-pointer hover:bg-blue-400" data-section="3"></div>
                    </div>
                    <button id="nextSection" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center">
                        Suivant
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Call to Action -->
                <div class="mt-8 text-center">
                    <button id="closeGuideBottom" class="bg-blue-600 text-white py-3 px-8 rounded-full text-lg font-medium hover:bg-blue-700 transition">Commencer à utiliser ClaimPro</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Button -->
    <button id="openFeedbackModal" class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition transform hover:scale-105 z-50">
        <i class="fas fa-comment-alt text-xl"></i>
    </button>

    <!-- Feedback Modal (New Form with Nom Utilisateur, Commentaire, Note) -->
    <div id="feedbackModal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-2xl shadow-xl max-w-[20rem] w-full hidden z-50">
        <button id="closeFeedbackModal" class="text-gray-700 hover:text-red-600 font-bold text-xl absolute top-2 right-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-xl font-kollektif text-blue-900 mb-4">Donnez Votre Avis</h2>
        <form action="https://fabform.io/f/{form-id}" method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-600">Nom Utilisateur:</label>
                <input type="text" id="username" name="username" required
                       class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm">
            </div>
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-600">Commentaire:</label>
                <textarea id="comment" name="comment" rows="3" required
                          class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm"
                          placeholder="Partagez vos impressions ou suggestions..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Note:</label>
                <div id="starRating" class="flex space-x-1 mt-1">
                    <i class="fas fa-star text-lg text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="1"></i>
                    <i class="fas fa-star text-lg text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="2"></i>
                    <i class="fas fa-star text-lg text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="3"></i>
                    <i class="fas fa-star text-lg text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="4"></i>
                    <i class="fas fa-star text-lg text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="5"></i>
                </div>
                <input type="hidden" id="ratingValue" name="rating" value="0">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelFeedback" class="bg-gray-200 text-gray-700 py-1.5 px-4 rounded-lg hover:bg-gray-300 transition text-sm">Annuler</button>
                <button type="submit" id="submitFeedback" class="bg-blue-600 text-white py-1.5 px-4 rounded-lg hover:bg-blue-700 transition text-sm">Soumettre</button>
            </div>
        </form>
        <a href="https://fabform.io" target="_blank" class="text-gray-700 hover:text-blue-500 text-xs mt-4 block text-center">Powered By FabForm.io</a>
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
                <p class="text-xl text-blue-100 mb-12">Rejoignez les entreprises qui font confiance à ClaimPro pour optimiser leur processus.</p>
                <a href="#contact" class="inline-block bg-white text-blue-900 font-kollektif py-4 px-12 rounded-full text-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">Commencer Maintenant</a>
            </div>
        </div>
    </section>

    <section>
        <div class="text-gray-900 pt-16 pb-24 px-6 w-full bg-gray-50">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6 text-gray-800">Our Impact </h2>
                <p class="text-lg text-gray-600 mb-16">Join thousands who trust our platform </p>
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
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-10">What People Are Saying</h2>
            <div class="flex flex-wrap gap-6 justify-center">
                <!-- Testimonial Card -->
                <div class="bg-white shadow-md rounded-2xl p-6 max-w-sm flex flex-col items-start hover:shadow-xl transition">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full border-2 border-indigo-500" src="https://i.pravatar.cc/150?img=12" alt="User avatar">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Sarah Williams</h4>
                            <p class="text-sm text-gray-500">Product Designer</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"This UI was a game-changer for our product launch. Everything was smooth and pixel-perfect. Highly recommend!"</p>
                </div>
                <!-- Testimonial Card -->
                <div class="bg-white shadow-md rounded-2xl p-6 max-w-sm flex flex-col items-start hover:shadow-xl transition">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full border-2 border-indigo-500" src="https://i.pravatar.cc/150?img=32" alt="User avatar">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">Michael</h4>
                            <p class="text-sm text-gray-500">Startup Founder</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Impressive responsiveness and design consistency. The attention to detail is what makes this shine!"</p>
                </div>
                <!-- Testimonial Card -->
                <div class="bg-white shadow-md rounded-2xl p-6 max-w-sm flex flex-col items-start hover:shadow-xl transition">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full border-2 border-indigo-500" src="https://i.pravatar.cc/150?img=22" alt="User avatar">
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">John</h4>
                            <p class="text-sm text-gray-500">Frontend Developer</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Clean layout, optimized for mobile, and extremely flexible with Tailwind. Loved using it!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for Modal Navigation, Feedback Modal, and Three.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Guide Modal Logic
            const guideModal = document.getElementById('guideModal');
            const closeGuide = document.getElementById('closeGuide');
            const closeGuideBottom = document.getElementById('closeGuideBottom');
            const sections = document.querySelectorAll('#guideModal .space-y-8 > div');
            const dots = document.querySelectorAll('#guideModal .flex.space-x-2 > div');
            let currentSection = 0;

            // Show guide modal after 1 second
            setTimeout(() => {
                guideModal.classList.remove('hidden');
            }, 1000);

            // Close guide modal
            closeGuide.addEventListener('click', () => {
                guideModal.classList.add('hidden');
            });
            closeGuideBottom.addEventListener('click', () => {
                guideModal.classList.add('hidden');
            });

            // Show specific section
            function showSection(index) {
                sections.forEach((section, i) => {
                    section.style.display = i === index ? 'block' : 'none';
                    dots[i].classList.toggle('bg-blue-600', i === index);
                    dots[i].classList.toggle('bg-blue-200', i !== index);
                });
                currentSection = index;
            }

            // Navigation buttons
            document.getElementById('nextSection').addEventListener('click', () => {
                if (currentSection < sections.length - 1) {
                    showSection(currentSection + 1);
                }
            });

            document.getElementById('prevSection').addEventListener('click', () => {
                if (currentSection > 0) {
                    showSection(currentSection - 1);
                }
            });

            // Clickable dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSection(index);
                });
            });

            // Initialize first section
            showSection(0);

            // Feedback Modal Logic
            const feedbackModal = document.getElementById('feedbackModal');
            const openFeedbackModal = document.getElementById('openFeedbackModal');
            const closeFeedbackModal = document.getElementById('closeFeedbackModal');
            const cancelFeedback = document.getElementById('cancelFeedback');
            const submitFeedback = document.getElementById('submitFeedback');
            const stars = document.querySelectorAll('#starRating .fa-star');
            const ratingValue = document.getElementById('ratingValue');

            // Open feedback modal
            openFeedbackModal.addEventListener('click', () => {
                feedbackModal.classList.remove('hidden');
            });

            // Close feedback modal
            closeFeedbackModal.addEventListener('click', () => {
                feedbackModal.classList.add('hidden');
                resetFeedbackForm();
            });

            // Cancel feedback
            cancelFeedback.addEventListener('click', () => {
                feedbackModal.classList.add('hidden');
                resetFeedbackForm();
            });

            // Star rating logic
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const rating = star.getAttribute('data-rating');
                    ratingValue.value = rating;
                    stars.forEach(s => {
                        s.classList.toggle('text-yellow-400', s.getAttribute('data-rating') <= rating);
                        s.classList.toggle('text-gray-300', s.getAttribute('data-rating') > rating);
                    });
                });
            });

            // Submit feedback (logs to console for front-end demo)
            submitFeedback.addEventListener('click', () => {
                const username = document.getElementById('username').value;
                const comment = document.getElementById('comment').value;
                const rating = ratingValue.value;
                console.log('Feedback submitted:', { username, comment, rating });
            });

            // Reset feedback form
            function resetFeedbackForm() {
                ratingValue.value = '0';
                stars.forEach(star => {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                });
                document.getElementById('username').value = '';
                document.getElementById('comment').value = '';
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