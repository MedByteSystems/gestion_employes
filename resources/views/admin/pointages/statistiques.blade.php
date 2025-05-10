<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Statistiques de pointage</h2>
                        <a href="{{ route('admin.pointages') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Retour aux pointages
                        </a>
                    </div>

                    <!-- Sélection de période -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('admin.pointages.statistiques') }}" method="GET" class="flex flex-wrap items-center gap-4">
                            <div>
                                <label for="periode" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                                <select id="periode" name="periode" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="semaine" {{ $periode == 'semaine' ? 'selected' : '' }}>7 derniers jours</option>
                                    <option value="mois" {{ $periode == 'mois' ? 'selected' : '' }}>30 derniers jours</option>
                                    <option value="trimestre" {{ $periode == 'trimestre' ? 'selected' : '' }}>3 derniers mois</option>
                                </select>
                            </div>
                            <div class="self-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Appliquer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Statistiques globales -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Statistiques globales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Total pointages -->
                            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Total pointages</h4>
                                        <p class="text-2xl font-bold">{{ $totalPointages }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total retards -->
                            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-amber-100 rounded-full mr-4">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Retards</h4>
                                        <p class="text-2xl font-bold">{{ $totalRetards }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $totalPointages > 0 ? round(($totalRetards / $totalPointages) * 100, 1) : 0 }}% des pointages
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total absences -->
                            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                                <div class="flex items-center">
                                    <div class="p-3 bg-red-100 rounded-full mr-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">Absences</h4>
                                        <p class="text-2xl font-bold">{{ $totalAbsences }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $totalPointages > 0 ? round(($totalAbsences / $totalPointages) * 100, 1) : 0 }}% des pointages
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques par département -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Statistiques par département</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Département
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Employés
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pointages
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Retards
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Taux de retard
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($statsDepartements as $departement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $departement->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $departement->employes_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $departement->pointages_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $departement->retards_count }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $departement->pointages_count > 0 ? round(($departement->retards_count / $departement->pointages_count) * 100, 1) : 0 }}%
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                                <div class="bg-amber-500 h-2.5 rounded-full" style="width: {{ $departement->pointages_count > 0 ? min(($departement->retards_count / $departement->pointages_count) * 100, 100) : 0 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if($statsDepartements->isEmpty())
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucune donnée disponible pour cette période
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
