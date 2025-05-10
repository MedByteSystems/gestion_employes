<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('Admin.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
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
                        <h1 class="text-3xl font-bold text-gray-900">Tableau de bord des absences</h1>
                        <p class="mt-2 text-gray-600">Vue d'ensemble et statistiques des absences</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        <a href="{{ route('admin.absences.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Liste des absences
                        </a>
                        <button onclick="location.href='{{ route('admin.absences.detecter') }}'" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Détecter les absences
                        </button>
                    </div>
                </div>

                <!-- Statistiques principales -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-red-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Non justifiées</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::where('statut', 'non_justifiée')->count() }}
                        </div>
                        <p class="text-sm text-gray-500">Absences à justifier</p>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">En attente</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::where('statut', 'en_attente')->count() }}
                        </div>
                        <p class="text-sm text-gray-500">Justifications à traiter</p>
                    </div>

                    <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Justifiées</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::where('statut', 'justifiée')->count() }}
                        </div>
                        <p class="text-sm text-gray-500">Absences approuvées</p>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Total</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::count() }}
                        </div>
                        <p class="text-sm text-gray-500">Absences enregistrées</p>
                    </div>
                </div>

                <!-- Absences récentes à traiter -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Justifications récentes à traiter</h2>
                    
                    <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Employé</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Motif</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $absencesEnAttente = \App\Models\Absence::where('statut', 'en_attente')
                                        ->with('employe')
                                        ->orderBy('updated_at', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @forelse($absencesEnAttente as $absence)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $absence->employe->first_name }} {{ $absence->employe->last_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($absence->date_debut->format('Y-m-d') == $absence->date_fin->format('Y-m-d'))
                                                {{ $absence->date_debut->format('d/m/Y') }}
                                            @else
                                                {{ $absence->date_debut->format('d/m/Y') }} - {{ $absence->date_fin->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $absence->motif ?: 'Non spécifié' }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('admin.absences.show', $absence) }}" class="text-blue-600 hover:text-blue-800">
                                                Traiter
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p>Aucune justification en attente</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(count($absencesEnAttente) > 0)
                        <div class="mt-4 text-right">
                            <a href="{{ route('admin.absences.index', ['statut' => 'en_attente']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir toutes les justifications en attente →
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Employés avec le plus d'absences -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Employés avec le plus d'absences</h2>
                    
                    <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Employé</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Département</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Total absences</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Non justifiées</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $topEmployes = \App\Models\Employe::withCount(['absences', 'absences as absences_non_justifiees_count' => function ($query) {
                                        $query->where('statut', 'non_justifiée');
                                    }])
                                    ->having('absences_count', '>', 0)
                                    ->orderBy('absences_count', 'desc')
                                    ->take(5)
                                    ->get();
                                @endphp
                                
                                @forelse($topEmployes as $employe)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $employe->first_name }} {{ $employe->last_name }}
                                        </td>
                                        <td class="px-6 py-4">{{ $employe->departement->name }}</td>
                                        <td class="px-6 py-4">{{ $employe->absences_count }}</td>
                                        <td class="px-6 py-4">
                                            @if($employe->absences_non_justifiees_count > 0)
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                                    {{ $employe->absences_non_justifiees_count }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                    0
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p>Aucune donnée disponible</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
