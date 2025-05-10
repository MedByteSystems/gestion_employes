<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.horaires.index', ['employe_id' => $employe->id]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour aux horaires de {{ $employe->first_name }}
                </a>
            </div>

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier l'horaire de {{ $employe->first_name }} {{ $employe->last_name }}</h1>
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.horaires.update', $horaire) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jour de la semaine</label>
                        <div class="py-2 px-3 bg-gray-100 rounded-lg text-gray-800">
                            {{ $joursSemaine[$horaire->jour_semaine] }}
                        </div>
                    </div>
                    
                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut', $horaire->heure_debut) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin', $horaire->heure_fin) }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="actif" name="actif" {{ $horaire->actif ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="actif" class="ml-2 block text-sm text-gray-700">Actif</label>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
