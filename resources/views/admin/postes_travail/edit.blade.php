<x-app-layout>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.postes-travail.index') }}" class="text-blue-600 hover:text-blue-800 mr-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Modifier un poste de travail</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Erreur</p>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700">Informations du poste de travail</h2>
        </div>
        
        <form action="{{ route('admin.postes-travail.update', ['postes_travail' => $posteTravail->id]) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employe_id" class="block text-sm font-medium text-gray-700 mb-1">Employé assigné au poste</label>
                    <select id="employe_id" name="employe_id" class="w-full py-2 px-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Sélectionnez un employé</option>
                        @foreach($employes as $employe)
                            <option value="{{ $employe->id }}" {{ old('employe_id', $posteTravail->employe_id) == $employe->id ? 'selected' : '' }}>{{ $employe->last_name }} {{ $employe->first_name }}</option>
                        @endforeach
                    </select>
                    @error('employe_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du poste</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $posteTravail->nom) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: PC Bureau, Laptop, etc." required>
                </div>
                
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <h3 class="font-medium text-blue-800 mb-2">Identifiant unique de ce poste de travail</h3>
                    <div class="flex items-center mb-2">
                        <p class="text-sm text-gray-600 mr-2">ID de l'appareil : <span class="font-mono font-medium">{{ $posteTravail->device_id ?? 'Non défini' }}</span></p>
                        <input type="hidden" name="device_id" id="device_id" value="{{ old('device_id', $posteTravail->device_id) }}">
                    </div>
                    <p class="text-xs text-gray-500">Cet identifiant est unique pour cet appareil et ne devrait pas être modifié.</p>
                </div>
                
                <!-- Les champs adresse_mac et adresse_ip ont été supprimés car ils ne sont plus utilisés -->
                
                <!-- Le champ adresse_ip a été supprimé -->
                
                <div>
                    <label for="localisation" class="block text-sm font-medium text-gray-700 mb-1">Localisation (optionnel)</label>
                    <input type="text" id="localisation" name="localisation" value="{{ old('localisation', $posteTravail->localisation) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Bureau 101, Étage 2, etc.">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="actif" name="actif" value="1" {{ old('actif', $posteTravail->actif) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="actif" class="ml-2 block text-sm text-gray-700">Poste actif</label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.postes-travail.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg mr-2">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Aucun script nécessaire pour la liste déroulante simple
</script>
@endpush
</x-app-layout>
