<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <!-- En-tête et Filtres -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <h1 class="text-2xl font-bold text-gray-800">Suivi des Pointages</h1>
                    
                    <div class="flex gap-4">
                        <select class="rounded-lg border-gray-200" id="departement">
                            <option value="">Tous les départements</option>
                            @foreach($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                            @endforeach
                        </select>

                        <select class="rounded-lg border-gray-200" id="periode">
                            <option value="mois">Ce mois</option>
                            <option value="semaine">Cette semaine</option>
                            <option value="trimestre">Ce trimestre</option>
                        </select>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Employé</th>
                                <th class="px-6 py-3 text-left">Département</th>
                                <th class="px-6 py-3 text-left">Statut Aujourd'hui</th>
                                <th class="px-6 py-3 text-left">Poste de travail</th>
                                <th class="px-6 py-3 text-left">Retard Cumulé</th>
                                <th class="px-6 py-3 text-left">Absences</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($employes as $employe)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pointages.employe', $employe) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $employe->full_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ $employe->departement->name }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $pointage = $employe->pointages->where('created_at', '>=', today())->first();
                                    @endphp
                                    
                                    @if(!$pointage)
                                        <span class="text-red-500">❌ Absent</span>
                                    @else
                                        <span class="{{ $pointage->statut === 'retard' ? 'text-yellow-500' : 'text-green-500' }}">
                                            {{ $pointage->statut === 'retard' ? '⚠ Retard' : '✅ À l\'heure' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $nbPostes = $employe->postesTravail()->count();
                                    @endphp
                                    
                                    @if($nbPostes > 0)
                                        <a href="{{ route('admin.employes.postes-travail', $employe) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $nbPostes }} poste(s) autorisé(s)
                                        </a>
                                    @else
                                        <span class="text-red-500">Aucun poste</span>
                                        <a href="{{ route('admin.postes-travail.create') }}?employe_id={{ $employe->id }}" class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                            + Ajouter
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $employe->pointages->sum('retard_minutes') }} minutes
                                </td>
                                <td class="px-6 py-4">
                                    {{ $employe->pointages->where('statut', 'absent')->count() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $employes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>