<x-app-layout>
    <!-- Toast de succès -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="fixed top-4 right-4 z-50">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <!-- En-tête -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-800">Ajouter un Employé</h1>
                        <p class="mt-2 text-gray-600">Remplissez les informations du nouvel employé</p>
                    </div>

                    <!-- Formulaire -->
                    <form method="POST" 
                    action="{{ auth()->user()->role === 'Admin' 
                        ? route('Admin.employes.store') 
                        : route('Manager.employes.store') }}" 
                    enctype="multipart/form-data">                      
                    
                    @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonne gauche -->
                            <div class="space-y-5">
                                <!-- Groupe Nom -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                    <input type="text" name="last_name" id="last_name" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe CIN -->
                                <div>
                                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-2">CIN</label>
                                    <input type="text" name="cin" id="cin" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('cin') }}" required>
                                    @error('cin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Date de Naissance -->
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de Naissance</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('birth_date') }}" required>
                                    @error('birth_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Poste -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
                                    <input type="text" name="position" id="position" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('position') }}" required>
                                    @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Département -->
                                <div>
                                    <label for="departement_id" class="block text-sm font-medium text-gray-700 mb-2">Département</label>
                                    <select name="departement_id" id="departement_id" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        <option value="">Sélectionnez un département</option>
                                        @foreach ($departements as $departement)
                                            <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                                {{ $departement->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('departement_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Colonne droite -->
                            <div class="space-y-5">
                                <!-- Groupe Prénom -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                    <input type="text" name="first_name" id="first_name" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Statut Familial -->
                                <div>
                                    <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Statut Familial</label>
                                    <input type="text" name="marital_status" id="marital_status" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('marital_status') }}" required>
                                    @error('marital_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Genre -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                                    <select name="gender" id="gender" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        <option value="">Sélectionnez un genre</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Date d'Embauche -->
                                <div>
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Date d'Embauche</label>
                                    <input type="date" name="hire_date" id="hire_date" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('hire_date') }}" required>
                                    @error('hire_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Photo -->
                                <div x-data="{ photoPreview: null }" class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Photo de profil</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 cursor-pointer hover:border-blue-500 transition-colors"
                                         @click="$refs.photo.click()"
                                         @drop.prevent="handleFileDrop"
                                         @dragover.prevent>
                                        <template x-if="!photoPreview">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    <span class="font-medium text-blue-600">Cliquez pour uploader</span><br>
                                                    ou glissez-déposez une image
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">PNG, JPG jusqu'à 2MB</p>
                                            </div>
                                        </template>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="h-32 w-32 rounded-full object-cover shadow-sm border-2 border-gray-200">
                                        </template>
                                    </div>
                                    <input type="file" 
                                           name="photo" 
                                           id="photo"
                                           x-ref="photo"
                                           class="hidden"
                                           accept="image/*"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file && file.type.startsWith('image/')) {
                                                   const reader = new FileReader();
                                                   reader.onload = (e) => photoPreview = e.target.result;
                                                   reader.readAsDataURL(file);
                                               } else {
                                                   photoPreview = null;
                                               }
                                           ">
                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Groupe Utilisateur -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Compte Utilisateur</label>
                                    <select name="user_id" id="user_id" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Enregistrer l'employé
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>