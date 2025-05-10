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
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Ajouter un horaire pour {{ $employe->first_name }} {{ $employe->last_name }}</h1>
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.horaires.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                    
                    <div>
                        <label for="jour_semaine" class="block text-sm font-medium text-gray-700 mb-1">Jour de la semaine</label>
                        <select id="jour_semaine" name="jour_semaine" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Choisir un jour --</option>
                            @foreach($joursSemaine as $key => $jour)
                                @if(!in_array($key, $joursConfigures))
                                    <option value="{{ $key }}" {{ old('jour_semaine') == $key ? 'selected' : '' }}>{{ $jour }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut', '09:00') }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin', '17:00') }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
