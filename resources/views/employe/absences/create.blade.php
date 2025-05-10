<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('Employé.absences.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la liste des absences
                </a>
            </div>

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Justifier une absence</h1>
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('Employé.absences.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="absence_id" value="{{ $absence->id }}">
                    
                    <!-- Période d'absence -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                            <div class="py-2 px-3 bg-gray-100 rounded-lg text-gray-800">
                                {{ $absence->date_debut->format('d/m/Y') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                            <div class="py-2 px-3 bg-gray-100 rounded-lg text-gray-800">
                                {{ $absence->date_fin->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Motif -->
                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700 mb-1">Motif de l'absence</label>
                        <select id="motif" name="motif" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="Maladie" {{ old('motif') == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                            <option value="Rendez-vous médical" {{ old('motif') == 'Rendez-vous médical' ? 'selected' : '' }}>Rendez-vous médical</option>
                            <option value="Rendez-vous administratif" {{ old('motif') == 'Rendez-vous administratif' ? 'selected' : '' }}>Rendez-vous administratif</option>
                            <option value="Problème familial" {{ old('motif') == 'Problème familial' ? 'selected' : '' }}>Problème familial</option>
                            <option value="Problème de transport" {{ old('motif') == 'Problème de transport' ? 'selected' : '' }}>Problème de transport</option>
                            <option value="Autre" {{ old('motif') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <!-- Justification -->
                    <div>
                        <label for="justification" class="block text-sm font-medium text-gray-700 mb-1">Détails de la justification</label>
                        <textarea id="justification" name="justification" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Veuillez fournir des détails sur votre absence..." required>{{ old('justification') }}</textarea>
                    </div>
                    
                    <!-- Document justificatif -->
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Document justificatif (facultatif)</label>
                        <input type="file" id="document" name="document" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Formats acceptés : PDF, JPG, PNG. Taille maximale : 5 Mo.</p>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            Soumettre la justification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
