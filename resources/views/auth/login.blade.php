<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center p-4" x-data="loginApp()">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-white bg-opacity-70"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, rgba(59, 130, 246, 0.05) 2%, transparent 0%), radial-gradient(circle at 75px 75px, rgba(99, 102, 241, 0.05) 2%, transparent 0%); background-size: 100px 100px;"></div>
        
        <!-- Login Container -->
        <div class="relative w-full max-w-md">
            <!-- Main Card -->
            <div class="bg-white backdrop-blur-lg bg-opacity-90 rounded-3xl shadow-2xl border border-white border-opacity-20 p-8 transform transition-all duration-300 hover:scale-105">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2">Bienvenue</h2>
                    <p class="text-gray-500 text-sm">Connectez-vous à votre compte</p>
                </div>

                <!-- Home Button -->
                <a href="{{ url('/') }}" class="w-full mb-6 inline-flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'accueil
                </a>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" @submit="handleSubmit" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Adresse Email
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
                                autofocus 
                                autocomplete="username"
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

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Mot de passe
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
                                autocomplete="current-password"
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

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 font-medium">
                                Se souvenir de moi
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-500 font-medium transition-colors duration-200" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-200 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="loading"
                        :class="loading ? 'cursor-not-allowed opacity-50' : ''"
                    >
                        <span x-show="!loading" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Se connecter
                        </span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Connexion en cours...
                        </span>
                    </button>
                </form>

                <!-- Separator -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">ou</span>
                    </div>
                </div>

                <!-- Register Button -->
                <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-200 rounded-xl text-gray-600 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Créer un compte
                </a>

                
            <!-- Decorative Elements -->
            <div class="absolute -top-4 -left-4 w-8 h-8 bg-blue-200 rounded-full opacity-60 animate-pulse"></div>
            <div class="absolute -bottom-6 -right-6 w-12 h-12 bg-indigo-200 rounded-full opacity-60 animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 -left-8 w-6 h-6 bg-purple-200 rounded-full opacity-40 animate-bounce delay-500"></div>
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
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 transform" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 0v4m0-4h4m-4 0H8"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Erreur de connexion</h3>
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
                        Réessayer
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
                <p class="text-gray-600 font-medium">Vérification en cours...</p>
                <p class="text-gray-400 text-sm mt-1">Veuillez patienter</p>
            </div>
        </div>
    </div>

    <script>
        function loginApp() {
            return {
                loading: false,
                showError: false,
                showPassword: false,
                errorMessage: '',

                init() {
                    // Check for existing errors from server
                    @if($errors->any())
                        this.showError = true;
                        this.errorMessage = this.translateError('{{ $errors->first() }}');
                        this.loading = false; // Make sure loading is false when there are errors
                    @endif

                    // Add input animations
                    this.setupInputAnimations();
                    
                    // Hide loading if page is fully loaded
                    window.addEventListener('load', () => {
                        this.loading = false;
                    });
                },

                translateError(error) {
                    const translations = {
                        'These credentials do not match our records.': 'Ces identifiants ne correspondent à aucun compte.',
                        'The provided credentials are incorrect.': 'Les identifiants fournis sont incorrects.',
                        'The email field is required.': 'Le champ email est obligatoire.',
                        'The password field is required.': 'Le champ mot de passe est obligatoire.',
                        'The email must be a valid email address.': 'L\'email doit être une adresse email valide.',
                        'Too many login attempts. Please try again in': 'Trop de tentatives de connexion. Veuillez réessayer dans',
                        'seconds.': 'secondes.',
                        'Your account has been locked.': 'Votre compte a été verrouillé.',
                        'Please verify your email address.': 'Veuillez vérifier votre adresse email.',
                        'Invalid credentials': 'Identifiants invalides',
                        'Authentication failed': 'Échec de l\'authentification'
                    };
                    
                    // Try to find exact match first
                    if (translations[error]) {
                        return translations[error];
                    }
                    
                    // Try partial matches for complex messages
                    for (let key in translations) {
                        if (error.includes(key)) {
                            return translations[key];
                        }
                    }
                    
                    // Return original if no translation found
                    return error;
                },

                togglePassword() {
                    this.showPassword = !this.showPassword;
                    const input = this.$refs.passwordInput;
                    input.type = this.showPassword ? 'text' : 'password';
                },

                handleSubmit(event) {
                    // Only show loading for valid forms
                    this.showError = false;
                    
                    // Check if form is valid before submitting
                    const form = event.target;
                    const email = form.email.value;
                    const password = form.password.value;
                    
                    if (!email || !password) {
                        this.showError = true;
                        this.errorMessage = 'Veuillez remplir tous les champs obligatoires.';
                        event.preventDefault();
                        return;
                    }
                    
                    // Show loading only after validation passes
                    this.loading = true;
                    
                    // Let the form submit normally
                    // Laravel will handle authentication and redirect or return with errors
                },

                resetForm() {
                    this.showError = false;
                    this.loading = false;
                    document.getElementById('password').value = '';
                    document.getElementById('email').focus();
                },

                setupInputAnimations() {
                    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
                    
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
                    const speed = (index + 1) * 0.5;
                    element.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
                });
            });

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('button, .btn');
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

        /* Success tick animation */
        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        .checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.8s ease-in-out forwards;
        }

        /* Success pulse animation */
        @keyframes successPulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .success-pulse {
            animation: successPulse 1s infinite;
        }

        /* Enhanced bounce for success icon */
        @keyframes successBounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -20px, 0);
            }
            70% {
                transform: translate3d(0, -10px, 0);
            }
            90% {
                transform: translate3d(0, -4px, 0);
            }
        }

        .success-bounce {
            animation: successBounce 1.2s ease-in-out;
        }

        /* Progress bar animation */
        @keyframes progressBar {
            0% {
                width: 0%;
            }
            100% {
                width: 100%;
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
    </style>
</x-guest-layout>