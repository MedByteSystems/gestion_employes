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
                        <h1 class="text-3xl font-bold text-gray-900">Gestion des Absences</h1>
                        <p class="mt-2 text-gray-600">Consultez et gérez les absences des employés</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0">
                        <button onclick="location.href='{{ route('admin.absences.detecter') }}'" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Détecter les absences
                        </button>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form method="GET" action="{{ route('admin.absences.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="employe_id" class="block text-sm font-medium text-gray-700 mb-1">Employé</label>
                            <select id="employe_id" name="employe_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous les employés</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->id }}" {{ request('employe_id') == $employe->id ? 'selected' : '' }}>
                                        {{ $employe->first_name }} {{ $employe->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="statut" name="statut" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="non_justifiée" {{ request('statut') == 'non_justifiée' ? 'selected' : '' }}>Non justifiée</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="justifiée" {{ request('statut') == 'justifiée' ? 'selected' : '' }}>Justifiée</option>
                                <option value="rejetée" {{ request('statut') == 'rejetée' ? 'selected' : '' }}>Rejetée</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Filtrer
                            </button>
                            <a href="{{ route('admin.absences.index') }}" class="ml-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tableau des absences -->
                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Employé</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Période</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Motif</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($absences as $absence)
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
                                        @if($absence->statut === 'justifiée')
                                            <span class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded-full">Justifiée</span>
                                        @elseif($absence->statut === 'en_attente')
                                            <span class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded-full">En attente</span>
                                        @elseif($absence->statut === 'rejetée')
                                            <span class="px-3 py-1 text-sm text-red-600 bg-red-100 rounded-full">Rejetée</span>
                                        @else
                                            <span class="px-3 py-1 text-sm text-yellow-600 bg-yellow-100 rounded-full">Non justifiée</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.absences.show', $absence) }}" class="text-blue-600 hover:text-blue-800">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p>Aucune absence trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $absences->withQueryString()->links() }}
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiques des absences</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                    </div>
                    
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">En attente</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::where('statut', 'en_attente')->count() }}
                        </div>
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
                    </div>
                    
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Rejetées</h3>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Absence::where('statut', 'rejetée')->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
