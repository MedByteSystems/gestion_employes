@extends('layouts.admin')

@section('title', 'Détails des pointages de ' . $employe->nom . ' ' . $employe->prenom)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.pointages') }}" class="text-blue-600 hover:text-blue-800 mr-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détails des pointages de {{ $employe->nom }} {{ $employe->prenom }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-700">Informations de l'employé</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Matricule</p>
                    <p class="font-medium">{{ $employe->matricule }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $employe->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Département</p>
                    <p class="font-medium">{{ $employe->departement->nom ?? 'Non assigné' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Historique des pointages</h2>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.employes.postes-travail', $employe) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Gérer les postes de travail
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Retard</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse MAC</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse IP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validation</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pointages as $pointage)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pointage->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pointage->heure_pointage->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pointage->statut === 'présent')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    À l'heure
                                </span>
                            @elseif($pointage->statut === 'retard')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Retard
                                </span>
                            @elseif($pointage->statut === 'absent')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Absent
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $pointage->statut }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($pointage->retard_minutes > 0)
                                <span class="text-red-600">{{ $pointage->retard_minutes }} minutes</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                            @php
                                $posteAutorise = $employe->postesTravail()->where('adresse_mac', $pointage->adresse_mac)->exists();
                            @endphp
                            
                            <span class="{{ $posteAutorise ? 'text-green-600' : 'text-red-600' }}">
                                {{ $pointage->adresse_mac }}
                            </span>
                            
                            @if(!$posteAutorise)
                                <a href="{{ route('admin.postes-travail.create') }}?employe_id={{ $employe->id }}&adresse_mac={{ $pointage->adresse_mac }}" class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                    Autoriser
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $pointage->adresse_ip }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pointage->validé)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Validé
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Non validé
                                </span>
                                
                                <form action="{{ route('admin.pointages.valider', $pointage) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-xs">
                                        Valider
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Aucun pointage enregistré pour cet employé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $pointages->links() }}
        </div>
    </div>
</div>
@endsection
