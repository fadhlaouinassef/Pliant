@php
    $userRole = strtolower(Auth::user()->role ?? 'citoyen');
    $roleColors = [
        'admin' => [
            'primary' => '#6366f1',
            'secondary' => '#8b5cf6',
            'light' => '#e0e7ff',
            'gradient' => 'from-indigo-500 to-purple-600'
        ],
        'agent' => [
            'primary' => '#0f766e',
            'secondary' => '#14b8a6',
            'light' => '#ccfbf1',
            'gradient' => 'from-teal-600 to-green-600'
        ],
        'citoyen' => [
            'primary' => '#1e40af',
            'secondary' => '#3b82f6',
            'light' => '#dbeafe',
            'gradient' => 'from-blue-600 to-blue-500'
        ]
    ];
    $colors = $roleColors[$userRole] ?? $roleColors['citoyen'];
@endphp

@extends("{$userRole}.dashboard")

@section('title', 'Mon Profil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r {{ $colors['gradient'] }} px-6 py-8">
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            @if(Auth::user()->image)
                                <img src="{{ asset('images/' . Auth::user()->image) }}" 
                                     alt="{{ Auth::user()->nom }}"
                                     class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="h-24 w-24 rounded-full bg-white/20 backdrop-blur border-4 border-white shadow-lg flex items-center justify-center">
                                    <span class="text-3xl font-bold text-white">
                                        {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-lg">
                                <svg class="w-5 h-5 text-{{ $userRole === 'admin' ? 'indigo' : ($userRole === 'agent' ? 'teal' : 'blue') }}-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.828-2.828z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-white mb-2">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h1> 
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($userRole) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white backdrop-blur">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    {{ Auth::user()->email }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Profile Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r {{ $colors['gradient'] }}">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Informations du Profil
                        </h2>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Quick Stats Sidebar -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r {{ $colors['gradient'] }}">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                            </svg>
                            Statistiques
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($userRole === 'citoyen')
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Réclamations</span>
                                <span class="text-lg font-bold text-blue-600">{{ Auth::user()->reclamations()->count() ?? 0 }}</span>
                            </div>
                        @elseif($userRole === 'agent')
                            <div class="flex justify-between items-center p-3 bg-teal-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Réclamations traitées</span>
                                <span class="text-lg font-bold text-teal-600">0</span>
                            </div>
                        @else
                            <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
                                <span class="text-lg font-bold text-indigo-600">{{ \App\Models\User::count() ?? 0 }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Membre since</span>
                            <span class="text-sm font-semibold text-gray-600">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r {{ $colors['gradient'] }}">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            Actions Rapides
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route($userRole . '.dashboard') }}" 
                           class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-{{ $userRole === 'admin' ? 'indigo' : ($userRole === 'agent' ? 'teal' : 'blue') }}-50 transition-colors group">
                            <svg class="w-5 h-5 mr-3 text-gray-600 group-hover:text-{{ $userRole === 'admin' ? 'indigo' : ($userRole === 'agent' ? 'teal' : 'blue') }}-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-{{ $userRole === 'admin' ? 'indigo' : ($userRole === 'agent' ? 'teal' : 'blue') }}-700">Retour au Dashboard</span>
                        </a>
                        
                        @if($userRole === 'citoyen')
                        <a href="#" 
                           class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 transition-colors group">
                            <svg class="w-5 h-5 mr-3 text-gray-600 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Mes Réclamations</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Sécurité du Compte
                    </h2>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Zone Dangereuse
                    </h2>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom role-based styling */
@php
    $primaryColor = $colors['primary'];
    $secondaryColor = $colors['secondary'];
    $lightColor = $colors['light'];
@endphp

.btn-role-primary {
    background: {{ $primaryColor }};
    border-color: {{ $primaryColor }};
}

.btn-role-primary:hover {
    background: {{ $secondaryColor }};
    border-color: {{ $secondaryColor }};
}

.text-role-primary {
    color: {{ $primaryColor }};
}

.bg-role-light {
    background-color: {{ $lightColor }};
}

/* Enhanced form styling */
.form-enhanced input,
.form-enhanced select,
.form-enhanced textarea {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 0.75rem;
    border: 2px solid #e5e7eb;
}

.form-enhanced input:focus,
.form-enhanced select:focus,
.form-enhanced textarea:focus {
    border-color: {{ $primaryColor }};
    box-shadow: 0 0 0 3px {{ $lightColor }};
    outline: none;
}

/* Animated save button */
@keyframes pulse-save {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.btn-save:hover {
    animation: pulse-save 0.6s ease-in-out;
}
</style>
@endsection
