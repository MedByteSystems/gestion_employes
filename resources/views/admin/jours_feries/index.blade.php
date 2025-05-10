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
                        <h1 class="text-3xl font-bold text-gray-900">Gestion des Jours Fériés</h1>
                        <p class="mt-2 text-gray-600">Consultez et gérez les jours fériés du système</p>
                    </div>
                    
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        <a href="{{ route('admin.jours-feries.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter un jour férié
                        </a>
                        <button onclick="document.getElementById('importForm').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Importer les jours fériés
                        </button>
                    </div>
                </div>

                <!-- Formulaire d'importation (caché par défaut) -->
                <div id="importForm" class="mb-6 bg-gray-50 p-4 rounded-lg hidden">
                    <form action="{{ route('admin.jours-feries.importer') }}" method="POST" class="flex items-end space-x-4">
                        @csrf
                        <div class="flex-1">
                            <label for="annee" class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                            <select id="annee" name="annee" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                @for ($i = date('Y'); $i <= date('Y') + 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Importer
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tableau des jours fériés -->
                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Nom</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Description</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($joursFeries as $jourFerie)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($jourFerie->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">{{ $jourFerie->nom }}</td>
                                    <td class="px-6 py-4">{{ $jourFerie->description }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.jours-feries.edit', $jourFerie) }}" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.jours-feries.destroy', $jourFerie) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce jour férié ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p>Aucun jour férié enregistré</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $joursFeries->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
