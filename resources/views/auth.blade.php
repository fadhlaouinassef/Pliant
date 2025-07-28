<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - Pliant</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/accueil.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Kollektif:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Kollektif', sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            min-height: 500px; /* Increased length */
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.7s ease-out;
        }

        .back-button {
            display: block;
            width: fit-content;
            margin: 0 auto 1.5rem auto; /* Centered with margin below */
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-family: 'Kollektif', sans-serif;
            font-weight: 700;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .back-button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .tab-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
            gap: 0.5rem; /* Added padding between buttons */
        }

        .tab-button {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #e5e7eb;
            color: #1f2937;
        }

        .tab-button.active {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: #ffffff;
        }

        .tab-button:hover:not(.active) {
            background: #d1d5db;
        }

        .auth-forms {
            position: relative;
            min-height: 350px;
        }

        .auth-form {
            position: absolute;
            width: 100%;
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .auth-form.active {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
            z-index: 10;
        }

        .auth-form h1 {
            font-size: 2rem;
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            font-family: 'Kollektif', sans-serif;
            font-size: 1rem;
            background: #f9fafb;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .login-button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: white;
            border-radius: 0.75rem;
            font-family: 'Kollektif', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .login-button:hover {
            transform: scale(1.03);
            background: linear-gradient(90deg, #1e40af, #1d4ed8);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
                min-height: 450px; /* Adjusted length for mobile */
            }
            .tab-button {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            .auth-form h1 {
                font-size: 1.5rem;
            }
            .input-field {
                font-size: 0.9rem;
            }
            .login-button {
                font-size: 1rem;
            }
            .back-button {
                padding: 0.5rem 1.25rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <button onclick="window.location.href='{{ url('/home') }}'" class="back-button">← Retour à l'accueil</button>
        <div class="tab-buttons">
            <button id="login-tab" class="tab-button">Connexion</button>
            <button id="register-tab" class="tab-button">Inscription</button>
        </div>
        <div class="auth-forms">
            <div id="login-form" class="auth-form">
                <h1>Connexion</h1>
                <form action="{{ route('auth') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="Votre email" class="input-field" required>
                    <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
                    <button type="submit" class="login-button">Se connecter</button>
                </form>
            </div>
            <div id="register-form" class="auth-form">
                <h1>Inscription</h1>
                <form action="{{ route('auth') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Votre nom" class="input-field" required>
                    <input type="email" name="email" placeholder="Votre email" class="input-field" required>
                    <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
                    <button type="submit" class="login-button">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            function activateTab(tab, form) {
                loginTab.classList.remove('active');
                registerTab.classList.remove('active');
                loginForm.classList.remove('active');
                registerForm.classList.remove('active');
                tab.classList.add('active');
                form.classList.add('active');
            }

            loginTab.addEventListener('click', () => activateTab(loginTab, loginForm));
            registerTab.addEventListener('click', () => activateTab(registerTab, registerForm));

            // Initial state
            activateTab(loginTab, loginForm);
        });
    </script>
</body>
</html>