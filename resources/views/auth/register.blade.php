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
            justify-content: center;
        }
        
        .register-wrapper {
            width: 100%;
            max-width: 1100px; /* Augmenté pour plus d'espace */
            margin: 0 auto;
        }
        
        .register-container {
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .register-container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h2 {
            color: var(--text-color);
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .form-columns {
            display: flex;
            gap: 1.5rem; /* Réduit l'écart entre les colonnes */
        }
        
        .form-column {
            flex: 1 1 45%; /* Ajuste la flexibilité pour plus d'espace par colonne */
        }
        
        .input-group {
            margin-bottom: 1.25rem;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 1rem; /* Augmenté pour lisibilité */
        }
        
        .input-group input,
        .input-group select,
        .input-group input[type="file"] {
            width: 100%;
            padding: 0.9rem 1.2rem; /* Augmenté pour plus d'espace interne */
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem; /* Augmenté pour lisibilité */
            transition: all 0.3s;
        }
        
        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .input-group .error-message {
            color: #ef4444;
            font-size: 0.9rem; /* Légèrement augmenté */
            margin-top: 0.25rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 1.1rem; /* Augmenté pour lisibilité */
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            width: 48%; /* Ajusté pour s'adapter avec le lien */
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--light-gray);
            color: var(--text-color);
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        
        .btn-home {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .btn-home:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .action-buttons {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1rem; /* Augmenté pour lisibilité */
            transition: color 0.2s;
        }
        
        .login-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
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
    </style>

    <div class="register-wrapper">
        <div class="register-container">
            <div class="register-header">
                <h2>Créer un compte</h2>
                <p>Veuillez remplir le formulaire pour vous inscrire</p>
            </div>

            <!-- Bouton vers la page d'accueil -->
            <a href="{{ url('/') }}" class="btn btn-home">
                ← Retour à l'accueil
            </a>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
                @csrf

                <div class="form-columns">
                    <!-- Colonne de gauche -->
                    <div class="form-column">
                        <!-- Nom -->
                        <div class="input-group">
                            <label for="nom">Nom</label>
                            <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required autofocus autocomplete="nom" placeholder="Entrez votre nom">
                            @error('nom')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div class="input-group">
                            <label for="prenom">Prénom</label>
                            <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required autocomplete="prenom" placeholder="Entrez votre prénom">
                            @error('prenom')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="input-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Entrez votre email">
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Numéro de téléphone -->
                        <div class="input-group">
                            <label for="num_tlph">Numéro de téléphone</label>
                            <input id="num_tlph" type="tel" name="num_tlph" value="{{ old('num_tlph') }}" required autocomplete="tel" placeholder="Entrez votre numéro">
                            @error('num_tlph')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="form-column">
                        <!-- Adresse -->
                        <div class="input-group">
                            <label for="adresse">Adresse</label>
                            <input id="adresse" type="text" name="adresse" value="{{ old('adresse') }}" required autocomplete="street-address" placeholder="Entrez votre adresse">
                            @error('adresse')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="input-group">
                            <label for="password">Mot de passe</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Créez un mot de passe">
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-group">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez le mot de passe">
                            @error('password_confirmation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Rôle -->
                        <div class="input-group">
                            <label for="role">Rôle</label>
                            <select id="role" name="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="citoyen" {{ old('role') == 'citoyen' ? 'selected' : '' }}>Citoyen</option>
                                <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="input-group">
                            <label for="image">Image</label>
                            <input id="image" type="file" name="image" accept="image/*">
                            @error('image')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a class="login-link" href="{{ route('login') }}">
                        Déjà inscrit ?
                    </a>

                    <button type="submit" class="btn btn-primary">
                        S'inscrire
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loader overlay -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="loader"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const loaderOverlay = document.getElementById('loaderOverlay');
            
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // Show loader
                    loaderOverlay.classList.add('active');
                    
                    // Fallback timeout after 10 seconds
                    setTimeout(() => {
                        loaderOverlay.classList.remove('active');
                    }, 10000);
                });
            }
            
            // Add input focus effects
            const inputs = document.querySelectorAll('input, select');
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