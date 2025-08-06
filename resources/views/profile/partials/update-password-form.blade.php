<section class="form-enhanced" x-data="{ showPasswords: false, strength: 0 }">
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Mot de passe actuel -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Mot de passe actuel
            </label>
            <div class="relative">
                <input id="update_password_current_password" 
                       name="current_password" 
                       :type="showPasswords ? 'text' : 'password'"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                       autocomplete="current-password" 
                       required>
                <button type="button" @click="showPasswords = !showPasswords"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg x-show="!showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                    </svg>
                    <svg x-show="showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nouveau mot de passe -->
        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">
                Nouveau mot de passe
            </label>
            <div class="relative">
                <input id="update_password_password" 
                       name="password" 
                       :type="showPasswords ? 'text' : 'password'"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                       autocomplete="new-password"
                       @input="checkPasswordStrength($event.target.value)"
                       required>
                <button type="button" @click="showPasswords = !showPasswords"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg x-show="!showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                    </svg>
                    <svg x-show="showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <!-- Indicateur de force du mot de passe -->
            <div class="mt-2">
                <div class="flex space-x-1">
                    <div class="h-2 flex-1 rounded-full" :class="strength >= 1 ? 'bg-red-500' : 'bg-gray-200'"></div>
                    <div class="h-2 flex-1 rounded-full" :class="strength >= 2 ? 'bg-orange-500' : 'bg-gray-200'"></div>
                    <div class="h-2 flex-1 rounded-full" :class="strength >= 3 ? 'bg-yellow-500' : 'bg-gray-200'"></div>
                    <div class="h-2 flex-1 rounded-full" :class="strength >= 4 ? 'bg-green-500' : 'bg-gray-200'"></div>
                </div>
                <p class="mt-1 text-xs text-gray-600" x-text="getStrengthText()"></p>
            </div>

            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmation du mot de passe -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                Confirmer le nouveau mot de passe
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" 
                       name="password_confirmation" 
                       :type="showPasswords ? 'text' : 'password'"
                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                       autocomplete="new-password" 
                       required>
                <button type="button" @click="showPasswords = !showPasswords"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg x-show="!showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                    </svg>
                    <svg x-show="showPasswords" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Conseils de sécurité -->
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-orange-800">Conseils pour un mot de passe sécurisé</h3>
                    <ul class="mt-2 text-sm text-orange-700 space-y-1">
                        <li>• Au moins 8 caractères</li>
                        <li>• Mélanger majuscules et minuscules</li>
                        <li>• Inclure des chiffres et des symboles</li>
                        <li>• Éviter les informations personnelles</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                @if (session('status') === 'password-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition 
                         x-init="setTimeout(() => show = false, 3000)"
                         class="flex items-center space-x-2 text-green-600 bg-green-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Mot de passe mis à jour!</span>
                    </div>
                @endif
            </div>

            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Mettre à jour le mot de passe
            </button>
        </div>
    </form>

    <script>
        function checkPasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
            
            this.strength = strength;
        }
        
        function getStrengthText() {
            switch(this.strength) {
                case 0: return 'Très faible';
                case 1: return 'Faible';
                case 2: return 'Moyen';
                case 3: return 'Fort';
                case 4: return 'Très fort';
                default: return '';
            }
        }
    </script>
</section>
