<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center p-4" x-data="registerApp()">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-white bg-opacity-70"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, rgba(59, 130, 246, 0.05) 2%, transparent 0%), radial-gradient(circle at 75px 75px, rgba(99, 102, 241, 0.05) 2%, transparent 0%); background-size: 100px 100px;"></div>
        
        <!-- Register Container -->
        <div class="relative w-full max-w-6xl">
            <!-- Main Card -->
            <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-3xl shadow-2xl border border-white border-opacity-20 p-10 transform transition-all duration-300">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">Créer un compte</h2>
                    <p class="text-gray-500 text-sm">Rejoignez notre plateforme en quelques étapes</p>
                </div>

                <!-- Home Button -->
                <a href="{{ url('/') }}" class="w-full mb-8 inline-flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'accueil
                </a>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" @submit="handleSubmit" class="space-y-6">
                    @csrf

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Left Column -->
                        <div class="space-y-8">
                            <!-- Nom Field -->
                            <div class="space-y-2">
                                <label for="nom" class="block text-sm font-semibold text-gray-700">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="nom" 
                                        type="text" 
                                        name="nom" 
                                        value="{{ old('nom') }}" 
                                        required 
                                        autofocus 
                                        autocomplete="family-name"
                                        placeholder="Votre nom de famille"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('nom') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('nom')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Prénom Field -->
                            <div class="space-y-2">
                                <label for="prenom" class="block text-sm font-semibold text-gray-700">
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="prenom" 
                                        type="text" 
                                        name="prenom" 
                                        value="{{ old('prenom') }}" 
                                        required 
                                        autocomplete="given-name"
                                        placeholder="Votre prénom"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('prenom') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('prenom')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-gray-700">
                                    Adresse Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autocomplete="email"
                                        placeholder="votre@email.com"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('email') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Téléphone Field -->
                            <div class="space-y-2">
                                <label for="num_tlph" class="block text-sm font-semibold text-gray-700">
                                    Numéro de téléphone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="num_tlph" 
                                        type="tel" 
                                        name="num_tlph" 
                                        value="{{ old('num_tlph') }}" 
                                        required 
                                        autocomplete="tel"
                                        placeholder="+33 6 12 34 56 78"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('num_tlph') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('num_tlph')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-8">
                            <!-- Adresse Field -->
                            <div class="space-y-2">
                                <label for="adresse" class="block text-sm font-semibold text-gray-700">
                                    Adresse <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="adresse" 
                                        type="text" 
                                        name="adresse" 
                                        value="{{ old('adresse') }}" 
                                        required 
                                        autocomplete="street-address"
                                        placeholder="123 Rue de la Paix, Paris"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('adresse') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('adresse')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-semibold text-gray-700">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="••••••••"
                                        class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('password') border-red-300 focus:ring-red-500 @enderror"
                                        x-ref="passwordInput"
                                    >
                                    <button 
                                        type="button" 
                                        @click="togglePassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <svg x-show="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Password Confirmation Field -->
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        id="password_confirmation" 
                                        type="password" 
                                        name="password_confirmation" 
                                        required 
                                        autocomplete="new-password"
                                        placeholder="••••••••"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-400 @error('password_confirmation') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Role Field -->
                            <div class="space-y-2">
                                <label for="role" class="block text-sm font-semibold text-gray-700">
                                    Rôle <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <select 
                                        id="role" 
                                        name="role" 
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 @error('role') border-red-300 focus:ring-red-500 @enderror appearance-none bg-white"
                                    >
                                        <option value="citoyen" {{ old('role', 'citoyen') == 'citoyen' ? 'selected' : '' }}>👤 Citoyen</option>
                                        <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>🛡️ Agent</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>⚡ Administrateur</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('role')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Image Field -->
                            <div class="space-y-2">
                                <label for="image" class="block text-sm font-semibold text-gray-700">
                                    Photo de profil
                                </label>
                                <div class="relative">
                                    <input 
                                        id="image" 
                                        type="file" 
                                        name="image" 
                                        accept="image/*"
                                        class="w-full py-3 px-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-300 focus:ring-red-500 @enderror"
                                    >
                                </div>
                                <p class="text-xs text-gray-500">Formats acceptés : JPG, PNG, GIF (max 2MB)</p>
                                @error('image')
                                    <p class="text-red-500 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-between pt-8 space-y-4 sm:space-y-0">
                        <a class="text-blue-600 hover:text-blue-500 font-medium transition-colors duration-200 flex items-center" href="{{ route('login') }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                            </svg>
                            Déjà inscrit ? Se connecter
                        </a>

                        <button 
                            type="submit" 
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading"
                            :class="loading ? 'cursor-not-allowed opacity-50' : ''"
                        >
                            <span x-show="!loading" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Créer mon compte
                            </span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Création en cours...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -top-4 -left-4 w-8 h-8 bg-blue-200 rounded-full opacity-60 animate-pulse"></div>
            <div class="absolute -bottom-6 -right-6 w-12 h-12 bg-indigo-200 rounded-full opacity-60 animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 -left-8 w-6 h-6 bg-purple-200 rounded-full opacity-40 animate-bounce delay-500"></div>
            <div class="absolute top-1/4 -right-10 w-10 h-10 bg-pink-200 rounded-full opacity-30 animate-pulse delay-700"></div>
        </div>

        <!-- Error Popup -->
        <div 
            x-show="showError" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
            @click="showError = false"
        >
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Erreur d'inscription</h3>
                    </div>
                    <button @click="showError = false" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 mb-6" x-text="errorMessage"></p>
                <div class="flex space-x-3">
                    <button 
                        @click="showError = false"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Fermer
                    </button>
                    <button 
                        @click="resetForm"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                    >
                        Corriger
                    </button>
                </div>
            </div>
        </div>

        <!-- Global Loading Overlay -->
        <div 
            x-show="loading" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-white bg-opacity-80 backdrop-blur-sm flex items-center justify-center"
        >
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mb-4"></div>
                <p class="text-gray-600 font-medium">Création du compte...</p>
                <p class="text-gray-400 text-sm mt-1">Veuillez patienter</p>
            </div>
        </div>
    </div>

    <script>
        function registerApp() {
            return {
                loading: false,
                showError: false,
                showPassword: false,
                errorMessage: '',

                init() {
                    // Check for existing errors from server
                    @if($errors->any())
                        this.showError = true;
                        this.errorMessage = this.getErrorMessage();
                    @endif

                    // Add input animations
                    this.setupInputAnimations();
                    
                    // Hide loading if page is fully loaded
                    window.addEventListener('load', () => {
                        this.loading = false;
                    });
                },

                getErrorMessage() {
                    const errors = @json($errors->all());
                    if (errors.length > 0) {
                        return errors.length === 1 ? errors[0] : `${errors.length} erreurs détectées. Veuillez vérifier vos informations.`;
                    }
                    return 'Une erreur est survenue lors de l\'inscription.';
                },

                togglePassword() {
                    this.showPassword = !this.showPassword;
                    const input = this.$refs.passwordInput;
                    input.type = this.showPassword ? 'text' : 'password';
                },

                handleSubmit(event) {
                    // Only show loading for valid forms
                    this.showError = false;
                    
                    // Basic client-side validation
                    const form = event.target;
                    const requiredFields = ['nom', 'prenom', 'email', 'num_tlph', 'adresse', 'password', 'password_confirmation'];
                    const emptyFields = requiredFields.filter(field => !form[field].value.trim());
                    
                    if (emptyFields.length > 0) {
                        this.showError = true;
                        this.errorMessage = 'Veuillez remplir tous les champs obligatoires.';
                        event.preventDefault();
                        return;
                    }
                    
                    // Check password confirmation
                    if (form.password.value !== form.password_confirmation.value) {
                        this.showError = true;
                        this.errorMessage = 'Les mots de passe ne correspondent pas.';
                        event.preventDefault();
                        return;
                    }
                    
                    // Show loading only after validation passes
                    this.loading = true;
                },

                resetForm() {
                    this.showError = false;
                    this.loading = false;
                    // Focus on first field
                    document.getElementById('nom').focus();
                },

                setupInputAnimations() {
                    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], select');
                    
                    inputs.forEach(input => {
                        input.addEventListener('focus', function() {
                            this.classList.add('ring-2', 'ring-blue-500', 'border-transparent');
                            this.classList.remove('border-gray-200');
                        });
                        
                        input.addEventListener('blur', function() {
                            this.classList.remove('ring-2', 'ring-blue-500', 'border-transparent');
                            this.classList.add('border-gray-200');
                        });
                    });
                }
            }
        }

        // Additional animations and effects
        document.addEventListener('DOMContentLoaded', function() {
            // Parallax effect for background elements
            document.addEventListener('mousemove', function(e) {
                const decorativeElements = document.querySelectorAll('.absolute');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                decorativeElements.forEach((element, index) => {
                    const speed = (index + 1) * 0.3;
                    element.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
                });
            });

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth animations */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* File input styling */
        input[type="file"]::-webkit-file-upload-button {
            visibility: hidden;
        }

        input[type="file"]::before {
            content: 'Choisir un fichier';
            display: inline-block;
            background: linear-gradient(to right, #3b82f6, #4f46e5);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }

        input[type="file"]:hover::before {
            background: linear-gradient(to right, #2563eb, #4338ca);
        }
    </style>
</x-guest-layout>