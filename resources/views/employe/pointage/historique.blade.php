<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('Employé.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au tableau de bord
                </a>
            </div>

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Historique des Pointages</h1>
                        <p class="mt-2 text-gray-600">Consultez vos pointages précédents</p>
                    </div>
                    
                    <!-- Filtre par mois -->
                    <div class="mt-4 md:mt-0">
                        <form method="GET" action="{{ route('Employé.pointage.historique') }}" class="flex items-center">
                            <label for="mois" class="mr-2 text-sm text-gray-700">Période :</label>
                            <select id="mois" name="mois" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Mois en cours</option>
                                @foreach($moisDisponibles as $mois)
                                    <option value="{{ $mois['valeur'] }}" {{ request('mois') == $mois['valeur'] ? 'selected' : '' }}>
                                        {{ $mois['libelle'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Tableau des pointages -->
                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Heure prévue</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Heure réelle</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Retard</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Poste de travail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pointages as $pointage)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $pointage->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">09:00</td>
                                    <td class="px-6 py-4">{{ $pointage->heure_reelle->format('H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if($pointage->statut === 'à l\'heure')
                                            <span class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded-full">À l'heure</span>
                                        @elseif($pointage->statut === 'retard')
                                            <span class="px-3 py-1 text-sm text-yellow-600 bg-yellow-100 rounded-full">Retard</span>
                                        @else
                                            <span class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-full">Absent</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pointage->retard_minutes > 0)
                                            <span class="text-yellow-600">{{ $pointage->retard_minutes }} min</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pointage->poste_travail_id && $pointage->posteTravail)
                                            <span class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded-full">{{ $pointage->posteTravail->nom }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p>Aucun pointage trouvé pour cette période</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pointages->withQueryString()->links() }}
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiques de pointage</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Statistique 1: Ponctualité -->
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Ponctualité</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ $pointages->where('statut', 'à l\'heure')->count() }}
                        </div>
                        <p class="text-sm text-gray-600">pointages à l'heure ce mois-ci</p>
                    </div>
                    
                    <!-- Statistique 2: Retards -->
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Retards</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ $pointages->where('statut', 'retard')->count() }}
                        </div>
                        <p class="text-sm text-gray-600">retards enregistrés ce mois-ci</p>
                    </div>
                    
                    <!-- Statistique 3: Taux de validation -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Validation</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            @if($pointages->count() > 0)
                                {{ round(($pointages->where('validé', true)->count() / $pointages->count()) * 100) }}%
                            @else
                                0%
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">taux de validation des pointages</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
