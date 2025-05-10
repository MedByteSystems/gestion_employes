<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Demander un congé</h1>
                <form method="POST" action="{{ route('Employé.conges.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Date Début -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Fin -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type de congé -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de congé</label>
                            <select name="type" id="type" 
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="Annuel" {{ old('type') == 'Annuel' ? 'selected' : '' }}>Annuel</option>
                                <option value="Maladie" {{ old('type') == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                                <option value="Maternité" {{ old('type') == 'Maternité' ? 'selected' : '' }}>Maternité</option>
                                <option value="Sans solde" {{ old('type') == 'Sans solde' ? 'selected' : '' }}>Sans solde</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Motif -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Motif</label>
                            <textarea name="reason" id="reason" 
                                      class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                      rows="4" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Envoyer la demande
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>