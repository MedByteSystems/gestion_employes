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
                        <h1 class="text-3xl font-bold text-gray-900">Mes Absences</h1>
                        <p class="mt-2 text-gray-600">Consultez et justifiez vos absences détectées par le système</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        @php
                            $absencesNonJustifiees = $absences->where('statut', 'non_justifiée')->count();
                        @endphp
                        
                        @if($absencesNonJustifiees > 0)
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-bold">Action requise</p>
                                        <p>Vous avez {{ $absencesNonJustifiees }} absence(s) à justifier</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6 flex flex-wrap gap-2">
                    <a href="{{ route('Employé.absences.index') }}" class="px-4 py-2 rounded-lg {{ request()->query('filtre') === null ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Toutes ({{ $absences->total() }})
                    </a>
                    <a href="{{ route('Employé.absences.index', ['filtre' => 'non_justifiee']) }}" class="px-4 py-2 rounded-lg {{ request()->query('filtre') === 'non_justifiee' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        À justifier ({{ $absences->where('statut', 'non_justifiée')->count() }})
                    </a>
                    <a href="{{ route('Employé.absences.index', ['filtre' => 'en_attente']) }}" class="px-4 py-2 rounded-lg {{ request()->query('filtre') === 'en_attente' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        En attente ({{ $absences->where('statut', 'en_attente')->count() }})
                    </a>
                    <a href="{{ route('Employé.absences.index', ['filtre' => 'justifiee']) }}" class="px-4 py-2 rounded-lg {{ request()->query('filtre') === 'justifiee' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Justifiées ({{ $absences->where('statut', 'justifiée')->count() }})
                    </a>
                    <a href="{{ route('Employé.absences.index', ['filtre' => 'rejetee']) }}" class="px-4 py-2 rounded-lg {{ request()->query('filtre') === 'rejetee' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Rejetées ({{ $absences->where('statut', 'rejetée')->count() }})
                    </a>
                </div>

                <!-- Tableau des absences -->
                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Période</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Motif</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($absences as $absence)
                                <tr class="{{ $absence->statut === 'non_justifiée' ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-gray-50' }} transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        @if($absence->date_debut->format('Y-m-d') == $absence->date_fin->format('Y-m-d'))
                                            {{ $absence->date_debut->format('d/m/Y') }}
                                        @else
                                            {{ $absence->date_debut->format('d/m/Y') }} - {{ $absence->date_fin->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $absence->motif ?: 'Non spécifié' }}</td>
                                    <td class="px-6 py-4">
                                        @if($absence->statut === 'justifiée')
                                            <span class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded-full">Justifiée</span>
                                        @elseif($absence->statut === 'en_attente')
                                            <span class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded-full">En attente</span>
                                        @elseif($absence->statut === 'rejetée')
                                            <span class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-full">Rejetée</span>
                                        @else
                                            <span class="px-3 py-1 text-sm text-yellow-600 bg-yellow-100 rounded-full font-medium">À justifier</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('Employé.absences.show', $absence) }}" class="text-blue-600 hover:text-blue-800">
                                            Détails
                                        </a>
                                        @if($absence->statut === 'non_justifiée')
                                            <a href="{{ route('Employé.absences.create', ['absence_id' => $absence->id]) }}" class="ml-3 inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Justifier
                                            </a>
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
                                            <p>Aucune absence enregistrée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $absences->links() }}
                </div>
            </div>

            <!-- Informations -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Comment justifier une absence ?</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3 mt-1">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Délai de justification</h3>
                            <p class="text-gray-600 text-sm">Vous disposez de 48 heures pour justifier une absence. Passé ce délai, l'absence sera considérée comme non justifiée.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3 mt-1">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Documents acceptés</h3>
                            <p class="text-gray-600 text-sm">Les justificatifs acceptés sont : certificats médicaux, convocations administratives, attestations diverses. Les documents doivent être au format PDF, JPG ou PNG.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
