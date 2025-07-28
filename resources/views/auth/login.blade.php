<x-guest-layout>
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --secondary-color: #60a5fa;
            --text-color: #1f2937;
            --light-gray: #f3f4f6;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 1rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center; /* Ajouté pour centrer verticalement et horizontalement */
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 480px; /* Augmenté de 420px à 480px */
            margin: 0 auto;
        }
        
        .login-container {
            padding: 2.5rem; /* Augmenté le padding */
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            width: 100%; /* Assure que le conteneur prend toute la largeur */
        }
        
        .login-container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .login-header h2 {
            color: var(--text-color);
            font-size: 1.75rem; /* Légèrement augmenté */
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 1rem; /* Légèrement augmenté */
        }
        
        .input-group {
            margin-bottom: 1.25rem; /* Légèrement augmenté */
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem; /* Légèrement augmenté */
        }
        
        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem; /* Légèrement augmenté */
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem; /* Légèrement augmenté */
            transition: all 0.3s;
        }
        
        /* Le reste du CSS reste inchangé */
        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 0.5rem;
            accent-color: var(--primary-color);
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        
        .forgot-password:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem; /* Légèrement augmenté */
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 1rem; /* Légèrement augmenté */
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            width: 100%;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--text-color);
            width: 100%;
            margin-top: 1rem; /* Légèrement augmenté */
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        
        .btn-home {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            width: 100%;
            margin-bottom: 1rem; /* Légèrement augmenté */
        }
        
        .btn-home:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .btn-google {
            background-color: white;
            color: #3c4043;
            border: 1px solid #dadce0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 1rem; /* Légèrement augmenté */
        }
        
        .btn-google:hover {
            background-color: #f8f9fa;
            border-color: #d2e3fc;
        }
        
        .btn-google img {
            width: 18px;
            height: 18px;
        }
        
        .action-buttons {
            margin-top: 1.5rem; /* Légèrement augmenté */
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.25rem 0; /* Légèrement augmenté */
            color: #6b7280;
            font-size: 0.9rem; /* Légèrement augmenté */
        }
        
        .separator::before,
        .separator::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .separator::before {
            margin-right: 0.75rem;
        }
        
        .separator::after {
            margin-left: 0.75rem;
        }
        
        /* Loader overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .loader-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Error messages */
        .error-message {
            color: #ef4444;
            font-size: 0.85rem; /* Légèrement augmenté */
            margin-top: 0.25rem;
        }
    </style>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <h2>Bienvenue</h2>
                <p>Veuillez entrer vos identifiants pour vous connecter</p>
            </div>

            <!-- Bouton vers la page d'accueil -->
            <a href="{{ url('/') }}" class="btn btn-home">
                ← Retour à l'accueil
            </a>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Address -->
                <div class="input-group">
                    <label for="email">Adresse Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Entrez votre email">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Entrez votre mot de passe">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Se souvenir de moi</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        Se connecter
                    </button>
                    
                    <a href="{{ route('register') }}" class="btn btn-secondary">
                        Créer un compte
                    </a>
                </div>
            </form>

            <!-- Séparateur -->
            <div class="separator">ou</div>

            <!-- Bouton Google -->
            <a href="{{ route('home') }}" class="btn btn-google">
                <img src="{{ asset('images/logo-google.jpeg') }}" alt="Google Logo" style="width:18px;height:18px;">
                Authentifier avec Google
            </a>
        </div>
    </div>

    <!-- Loader overlay -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loaderOverlay = document.getElementById('loaderOverlay');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Show loader
                    loaderOverlay.classList.add('active');
                    
                    // You could add a timeout to hide the loader if the request takes too long
                    // For a real application, you would hook into the fetch/axios promise
                    setTimeout(() => {
                        loaderOverlay.classList.remove('active');
                    }, 10000); // Fallback timeout after 10 seconds
                });
            }
            
            // Add input focus effects
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentNode.querySelector('label').style.color = 'var(--primary-color)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentNode.querySelector('label').style.color = 'var(--text-color)';
                });
            });
            
            // Add button hover effects
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>
</x-guest-layout>