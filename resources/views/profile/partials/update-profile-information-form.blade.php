<section class="form-enhanced">
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" 
          class="space-y-6" x-data="{ uploading: false, saved: false }">
        @csrf
        @method('patch')

        <!-- Photo de profil -->
        <div class="space-y-4">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Photo de profil</label>
            <div class="flex items-center space-x-6">
                <div class="relative group">
                    @if(Auth::user()->image)
                        <img id="profile-preview" src="{{ asset('images/' . Auth::user()->image) }}" 
                             alt="Photo de profil" 
                             class="h-20 w-20 rounded-full object-cover border-4 border-gray-200 group-hover:border-blue-400 transition-colors">
                    @else
                        <div id="profile-preview" class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center border-4 border-gray-200 group-hover:border-blue-400 transition-colors">
                            <span class="text-2xl font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->nom ?? Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" 
                           onchange="previewImage(this)">
                    <button type="button" onclick="document.getElementById('image').click()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        Changer la photo
                    </button>
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG ou GIF (max. 2MB)</p>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                <input id="nom" name="nom" type="text" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       value="{{ old('nom', Auth::user()->nom ?? Auth::user()->name) }}" 
                       required>
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prenom" class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                <input id="prenom" name="prenom" type="text" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       value="{{ old('prenom', Auth::user()->prenom) }}">
                @error('prenom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email et téléphone -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                <input id="email" name="email" type="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       value="{{ old('email', Auth::user()->email) }}" 
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            Votre adresse email n'est pas vérifiée.
                            <button form="send-verification" class="underline text-yellow-900 hover:text-yellow-700 font-medium">
                                Cliquez ici pour renvoyer l'email de vérification.
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <div>
                <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                <input id="telephone" name="telephone" type="tel" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       value="{{ old('telephone', Auth::user()->num_tlph) }}"
                       placeholder="+216 XX XXX XXX">
                @error('telephone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Adresse -->
        <div>
            <label for="adresse" class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
            <textarea id="adresse" name="adresse" rows="3" 
                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                      placeholder="Votre adresse complète...">{{ old('adresse', Auth::user()->adresse) }}</textarea>
            @error('adresse')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                <!-- Messages de statut -->
                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition 
                         x-init="setTimeout(() => show = false, 3000)"
                         class="flex items-center space-x-2 text-green-600 bg-green-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Profil mis à jour avec succès!</span>
                    </div>
                @endif

                @if (session('status') === 'verification-link-sent')
                    <div class="flex items-center space-x-2 text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-sm font-medium">Email de vérification envoyé!</span>
                    </div>
                @endif
            </div>

            <button type="submit" 
                    :disabled="uploading"
                    class="btn-save inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg x-show="!uploading" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                <svg x-show="uploading" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="uploading ? 'Enregistrement...' : 'Enregistrer les modifications'"></span>
            </button>
        </div>
    </form>

    <!-- Form de vérification email -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
</section>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('profile-preview');
        
        reader.onload = function(e) {
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Remplacer le div par une image
                const img = document.createElement('img');
                img.id = 'profile-preview';
                img.src = e.target.result;
                img.alt = 'Photo de profil';
                img.className = 'h-20 w-20 rounded-full object-cover border-4 border-gray-200 group-hover:border-blue-400 transition-colors';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
