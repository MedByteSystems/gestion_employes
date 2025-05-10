<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <!-- En-tête avec mise en évidence -->
                    <div class="mb-8 border-b border-gray-200 pb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-bold text-gray-800">Fiche de modification</h1>
                            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                ID : {{ $employe->id }}
                            </div>
                        </div>
                        
                        <!-- Bannière d'identification -->
                        <div class="bg-gray-50 p-4 rounded-lg flex items-center space-x-4">
                            @if($employe->photo)
                                <img src="{{ asset('storage/' . $employe->photo) }}" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm">
                            @else
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ $employe->first_name }} {{ $employe->last_name }}</h2>
                                <p class="text-sm text-gray-600">
                                    {{ $employe->position }} - 
                                    <span class="text-blue-600">{{ $employe->departement->name }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire complet -->
                    <form method="POST" 
                    action="{{ auth()->user()->role === 'Admin' 
                        ? route('Admin.employes.update', $employe->id) 
                        : route('Manager.employes.update', $employe->id) }}" 
                    enctype="multipart/form-data">                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonne gauche -->
                            <div class="space-y-5">
                                <!-- Nom -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de famille</label>
                                    <input type="text" name="last_name" id="last_name" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('last_name', $employe->last_name) }}" required>
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CIN -->
                                <div>
                                    <label for="cin" class="block text-sm font-medium text-gray-700 mb-2">Numéro CIN</label>
                                    <input type="text" name="cin" id="cin" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('cin', $employe->cin) }}" required>
                                    @error('cin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date de Naissance -->
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('birth_date', $employe->birth_date->format('Y-m-d')) }}" required>
                                    @error('birth_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Poste -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Poste actuel</label>
                                    <input type="text" name="position" id="position" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('position', $employe->position) }}" required>
                                    @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Département -->
                                <div>
                                    <label for="departement_id" class="block text-sm font-medium text-gray-700 mb-2">Département</label>
                                    <select name="departement_id" id="departement_id" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        @foreach ($departements as $departement)
                                            <option value="{{ $departement->id }}" 
                                                {{ old('departement_id', $employe->departement_id) == $departement->id ? 'selected' : '' }}>
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
                                <!-- Prénom -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                    <input type="text" name="first_name" id="first_name" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('first_name', $employe->first_name) }}" required>
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Statut Familial -->
                                <div>
                                    <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Statut familial</label>
                                    <select name="marital_status" id="marital_status" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        <option value="Célibataire" {{ old('marital_status', $employe->marital_status) == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="Marié(e)" {{ old('marital_status', $employe->marital_status) == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="Divorcé(e)" {{ old('marital_status', $employe->marital_status) == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                                    </select>
                                    @error('marital_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Genre -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                                    <select name="gender" id="gender" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        <option value="male" {{ old('gender', $employe->gender) == 'male' ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ old('gender', $employe->gender) == 'female' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date d'Embauche -->
                                <div>
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Date d'embauche</label>
                                    <input type="date" name="hire_date" id="hire_date" 
                                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                           value="{{ old('hire_date', $employe->hire_date->format('Y-m-d')) }}" required>
                                    @error('hire_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Compte Utilisateur -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Compte utilisateur associé</label>
                                    <select name="user_id" id="user_id" 
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ old('user_id', $employe->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Photo -->
                                <div x-data="{ photoPreview: '{{ $employe->photo ? asset('storage/' . $employe->photo) : '' }}' }" class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Photo de profil</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 cursor-pointer hover:border-blue-500 transition-colors"
                                         @click="$refs.photo.click()">
                                        <template x-if="!photoPreview">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    Cliquez pour changer la photo
                                                </p>
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
                                    @if($employe->photo)
                                    <div class="mt-2 text-sm text-gray-500">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="remove_photo" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                            <span class="ml-2">Supprimer la photo actuelle</span>
                                        </label>
                                    </div>
                                    @endif
                                    @error('photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>