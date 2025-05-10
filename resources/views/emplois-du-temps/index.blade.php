<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Emploi du temps de l\'équipe') }} : {{ $equipe->nom }}
            </h2>
            <a href="{{ route('equipes.emplois-du-temps.create', $equipe) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Ajouter une activité
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('equipes.show', $equipe) }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Retour aux détails de l'équipe
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($emploisDuTemps->isEmpty())
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600 mb-2">Aucun emploi du temps n'a encore été créé pour cette équipe</p>
                            <a href="{{ route('equipes.emplois-du-temps.create', $equipe) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Créer un emploi du temps
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-7 gap-4 mb-6">
                            @php
                                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                                $emploisDuTempsParJour = $emploisDuTemps->groupBy('jour_semaine');
                            @endphp

                            @foreach ($jours as $jour)
                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                    <div class="bg-gray-200 p-3 text-center font-semibold">
                                        {{ $jour }}
                                    </div>
                                    <div class="p-3 space-y-2 min-h-[200px]">
                                        @if (isset($emploisDuTempsParJour[$jour]))
                                            @foreach ($emploisDuTempsParJour[$jour] as $emploiDuTemps)
                                                <div class="bg-white border rounded-md p-3 text-sm relative group hover:shadow-md transition-shadow">
                                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <div class="flex space-x-1">
                                                            <a href="{{ route('equipes.emplois-du-temps.edit', [$equipe, $emploiDuTemps]) }}" class="text-blue-600 hover:text-blue-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </a>
                                                            <form action="{{ route('equipes.emplois-du-temps.destroy', [$equipe, $emploiDuTemps]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette activité?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-1 font-semibold text-gray-800">{{ $emploiDuTemps->titre }}</div>
                                                    <div class="text-xs text-gray-500 mb-1">
                                                        {{ \Carbon\Carbon::parse($emploiDuTemps->heure_debut)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($emploiDuTemps->heure_fin)->format('H:i') }}
                                                    </div>
                                                    
                                                    @if ($emploiDuTemps->lieu)
                                                        <div class="text-xs text-gray-600 mb-1">
                                                            <span class="font-medium">Lieu:</span> {{ $emploiDuTemps->lieu }}
                                                        </div>
                                                    @endif
                                                    
                                                    @if ($emploiDuTemps->type_activite)
                                                        <div class="text-xs inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                            {{ $emploiDuTemps->type_activite }}
                                                        </div>
                                                    @endif
                                                    
                                                    @if (!$emploiDuTemps->recurrent)
                                                        <div class="text-xs inline-block px-2 py-1 rounded-full bg-purple-100 text-purple-800 ml-1">
                                                            {{ \Carbon\Carbon::parse($emploiDuTemps->date_specifique)->format('d/m/Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-gray-500 text-sm py-4">
                                                Aucune activité
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4">Activités non récurrentes à venir</h3>
                            
                            @php
                                $activitesNonRecurrentes = $emploisDuTemps->where('recurrent', false)
                                    ->where('date_specifique', '>=', now()->format('Y-m-d'))
                                    ->sortBy('date_specifique');
                            @endphp
                            
                            @if ($activitesNonRecurrentes->isEmpty())
                                <p class="text-gray-500">Aucune activité non récurrente à venir</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horaire</th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lieu</th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activitesNonRecurrentes as $activite)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="py-2 px-4 border-b border-gray-200">
                                                        {{ \Carbon\Carbon::parse($activite->date_specifique)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b border-gray-200 font-medium">{{ $activite->titre }}</td>
                                                    <td class="py-2 px-4 border-b border-gray-200">
                                                        {{ \Carbon\Carbon::parse($activite->heure_debut)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($activite->heure_fin)->format('H:i') }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b border-gray-200">{{ $activite->lieu ?? '-' }}</td>
                                                    <td class="py-2 px-4 border-b border-gray-200">
                                                        @if ($activite->type_activite)
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                {{ $activite->type_activite }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 border-b border-gray-200">
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('equipes.emplois-du-temps.edit', [$equipe, $activite]) }}" class="text-blue-600 hover:text-blue-900">Modifier</a>
                                                            <form action="{{ route('equipes.emplois-du-temps.destroy', [$equipe, $activite]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette activité?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
