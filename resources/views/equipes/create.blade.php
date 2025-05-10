<x-app-layout>
    <!-- Ajout de Select2 pour l'autocomplétion -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une nouvelle équipe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('equipes.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'équipe</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nom')
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

                        <div class="mb-4">
                            <label for="responsable_id" class="block text-sm font-medium text-gray-700">Responsable d'équipe</label>
                            <select name="responsable_id" id="responsable_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 select2-responsable">
                                <option value="">Sélectionner un responsable</option>
                                @if (old('responsable_id'))
                                    @php
                                        $selectedResponsable = \App\Models\Employe::find(old('responsable_id'));
                                    @endphp
                                    @if ($selectedResponsable)
                                        <option value="{{ $selectedResponsable->id }}" selected>
                                            {{ $selectedResponsable->first_name }} {{ $selectedResponsable->last_name }}
                                        </option>
                                    @endif
                                @endif
                            </select>
                            @error('responsable_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="employes" class="block text-sm font-medium text-gray-700 mb-2">Membres de l'équipe</label>
                            <select name="employes[]" id="employes" multiple
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 select2-membres">
                                @if (old('employes'))
                                    @php
                                        $selectedEmployes = \App\Models\Employe::whereIn('id', old('employes', []))->get();
                                    @endphp
                                    @foreach ($selectedEmployes as $employe)
                                        <option value="{{ $employe->id }}" selected>
                                            {{ $employe->first_name }} {{ $employe->last_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Recherchez et sélectionnez plusieurs employés</p>
                            @error('employes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('equipes.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Créer l'équipe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        // Initialisation de Select2 pour le responsable d'équipe
        $('.select2-responsable').select2({
            placeholder: 'Rechercher un responsable...',
            allowClear: true,
            minimumInputLength: 2,
            language: {
                inputTooShort: function() {
                    return "Veuillez saisir au moins 2 caractères";
                },
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            },
            ajax: {
                url: '{{ route("search-employes") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        query: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatEmploye,
            templateSelection: formatEmployeSelection
        });
        
        // Initialisation de Select2 pour les membres de l'équipe
        $('.select2-membres').select2({
            placeholder: 'Rechercher des employés...',
            allowClear: true,
            minimumInputLength: 2,
            language: {
                inputTooShort: function() {
                    return "Veuillez saisir au moins 2 caractères";
                },
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            },
            ajax: {
                url: '{{ route("search-employes") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        query: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatEmploye,
            templateSelection: formatEmployeSelection
        });
        
        // Fonction pour formater les résultats de recherche avec photo
        function formatEmploye(employe) {
            if (!employe.id) {
                return employe.text;
            }
            
            var $container = $(
                '<div class="select2-result-employe">' +
                    '<div class="select2-result-employe__avatar">' +
                        (employe.photo ? '<img src="' + employe.photo + '" />' : '<div class="avatar-placeholder">' + employe.text.charAt(0) + '</div>') +
                    '</div>' +
                    '<div class="select2-result-employe__meta">' +
                        '<div class="select2-result-employe__title">' + employe.text + '</div>' +
                    '</div>' +
                '</div>'
            );
            
            return $container;
        }
        
        // Fonction pour formater la sélection
        function formatEmployeSelection(employe) {
            return employe.text || employe.id;
        }
    });
    </script>
    
    <style>
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single {
        border-color: rgb(209, 213, 219);
        border-radius: 0.375rem;
        min-height: 38px;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.25rem;
        padding: 2px 8px;
        margin-top: 4px;
    }
    
    .select2-result-employe {
        display: flex;
        align-items: center;
        padding: 6px 0;
    }
    
    .select2-result-employe__avatar {
        margin-right: 8px;
    }
    
    .select2-result-employe__avatar img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6b7280;
    }
    </style>
</x-app-layout>
