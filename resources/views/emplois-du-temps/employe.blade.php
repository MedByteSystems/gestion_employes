<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes emplois du temps d\'équipe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($equipes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-600 mb-2">Vous n'appartenez à aucune équipe pour le moment</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Activités d'aujourd'hui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Activités d'aujourd'hui</h3>
                        
                        @php
                            $jourActuel = \Carbon\Carbon::now()->locale('fr_FR')->isoFormat('dddd');
                            $jourActuel = ucfirst($jourActuel); // Première lettre en majuscule
                            $dateActuelle = \Carbon\Carbon::now()->format('Y-m-d');
                            
                            $activitesAujourdhui = collect();
                            
                            foreach ($equipes as $equipe) {
                                // Activités récurrentes pour le jour actuel
                                $activitesRecurrentes = $equipe->emploisDuTemps()
                                    ->where('jour_semaine', $jourActuel)
                                    ->where('recurrent', true)
                                    ->orderBy('heure_debut')
                                    ->get();
                                
                                // Activités spécifiques pour la date actuelle
                                $activitesSpecifiques = $equipe->emploisDuTemps()
                                    ->where('recurrent', false)
                                    ->whereDate('date_specifique', $dateActuelle)
                                    ->orderBy('heure_debut')
                                    ->get();
                                
                                // Fusionner les deux collections
                                $activitesEquipe = $activitesRecurrentes->concat($activitesSpecifiques);
                                
                                // Ajouter le nom de l'équipe à chaque activité
                                $activitesEquipe->each(function($activite) use ($equipe) {
                                    $activite->nom_equipe = $equipe->nom;
                                });
                                
                                $activitesAujourdhui = $activitesAujourdhui->concat($activitesEquipe);
                            }
                            
                            // Trier par heure de début
                            $activitesAujourdhui = $activitesAujourdhui->sortBy('heure_debut');
                        @endphp
                        
                        @if ($activitesAujourdhui->isEmpty())
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-gray-600">Aucune activité prévue pour aujourd'hui</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horaire</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activité</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Équipe</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lieu</th>
                                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activitesAujourdhui as $activite)
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    {{ \Carbon\Carbon::parse($activite->heure_debut)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($activite->heure_fin)->format('H:i') }}
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200 font-medium">
                                                    {{ $activite->titre }}
                                                    @if ($activite->description)
                                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($activite->description, 50) }}</p>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $activite->nom_equipe }}
                                                    </span>
                                                </td>
                                                <td class="py-2 px-4 border-b border-gray-200">{{ $activite->lieu ?? '-' }}</td>
                                                <td class="py-2 px-4 border-b border-gray-200">
                                                    @if ($activite->type_activite)
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $activite->type_activite }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Emplois du temps par équipe -->
                @foreach ($equipes as $equipe)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Équipe : {{ $equipe->nom }}</h3>
                                
                                @if($equipe->emploi_du_temps_pdf)
                                <a href="{{ route('Employé.equipes.download-pdf', $equipe) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Télécharger l'emploi du temps PDF
                                </a>
                                @endif
                            </div>
                            
                            @if (!$equipe->emploi_du_temps_pdf)
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <div class="flex justify-center mb-3">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-600">Aucun emploi du temps n'a été téléversé pour cette équipe</p>
                                </div>
                            @else
                                <div class="bg-white border rounded-lg overflow-hidden shadow-sm p-4">
                                    <div class="flex items-center mb-4">
                                        <svg class="w-10 h-10 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h-6l2 3-2 3h6l-2-3 2-3z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium">{{ $equipe->emploi_du_temps_nom ?: 'Emploi du temps.pdf' }}</p>
                                            <p class="text-sm text-gray-500">Emploi du temps au format PDF</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <p class="text-gray-600 mb-4">Consultez l'emploi du temps complet de votre équipe en téléchargeant le fichier PDF.</p>
                                        
                                        <a href="{{ route('Employé.equipes.download-pdf', $equipe) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger l'emploi du temps
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
