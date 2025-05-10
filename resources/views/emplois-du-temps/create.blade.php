<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une activité à l\'emploi du temps') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('equipes.emplois-du-temps.index', $equipe) }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Retour à l'emploi du temps
                        </a>
                    </div>

                    <form method="POST" action="{{ route('equipes.emplois-du-temps.store', $equipe) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="titre" class="block text-sm font-medium text-gray-700">Titre de l'activité</label>
                            <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('titre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="jour_semaine" class="block text-sm font-medium text-gray-700">Jour de la semaine</label>
                                <select name="jour_semaine" id="jour_semaine" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sélectionner un jour</option>
                                    @foreach ($joursDelaSemaine as $jour)
                                        <option value="{{ $jour }}" {{ old('jour_semaine') == $jour ? 'selected' : '' }}>
                                            {{ $jour }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jour_semaine')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_activite" class="block text-sm font-medium text-gray-700">Type d'activité</label>
                                <select name="type_activite" id="type_activite"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Sélectionner un type</option>
                                    @foreach ($typesActivite as $type)
                                        <option value="{{ $type }}" {{ old('type_activite') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_activite')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="heure_debut" class="block text-sm font-medium text-gray-700">Heure de début</label>
                                <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('heure_debut')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_fin" class="block text-sm font-medium text-gray-700">Heure de fin</label>
                                <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('heure_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="lieu" class="block text-sm font-medium text-gray-700">Lieu</label>
                            <input type="text" name="lieu" id="lieu" value="{{ old('lieu') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('lieu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="recurrent" id="recurrent" value="1" {{ old('recurrent', '1') == '1' ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    onchange="toggleDateSpecifique()">
                                <label for="recurrent" class="ml-2 block text-sm text-gray-900">
                                    Activité récurrente (chaque semaine)
                                </label>
                            </div>
                            @error('recurrent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="date_specifique_container" class="mb-4" style="display: none;">
                            <label for="date_specifique" class="block text-sm font-medium text-gray-700">Date spécifique</label>
                            <input type="date" name="date_specifique" id="date_specifique" value="{{ old('date_specifique') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Requis pour les activités non récurrentes</p>
                            @error('date_specifique')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('equipes.emplois-du-temps.index', $equipe) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Ajouter l'activité
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDateSpecifique() {
            const recurrentCheckbox = document.getElementById('recurrent');
            const dateSpecifiqueContainer = document.getElementById('date_specifique_container');
            
            if (recurrentCheckbox.checked) {
                dateSpecifiqueContainer.style.display = 'none';
                document.getElementById('date_specifique').removeAttribute('required');
            } else {
                dateSpecifiqueContainer.style.display = 'block';
                document.getElementById('date_specifique').setAttribute('required', 'required');
            }
        }
        
        // Exécuter au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            toggleDateSpecifique();
        });
    </script>
</x-app-layout>
