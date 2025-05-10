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
                        <h1 class="text-3xl font-bold text-gray-900">Gestion des Horaires</h1>
                        <p class="mt-2 text-gray-600">Configurez les horaires personnalisés des employés</p>
                    </div>
                </div>

                <!-- Sélection de l'employé -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <form action="{{ route('admin.horaires.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                        <div class="flex-1">
                            <label for="employe_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionnez un employé</label>
                            <select id="employe_id" name="employe_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Choisir un employé --</option>
                                @foreach($employes as $e)
                                    <option value="{{ $e->id }}" {{ $employe && $employe->id == $e->id ? 'selected' : '' }}>
                                        {{ $e->first_name }} {{ $e->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Afficher les horaires
                            </button>
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($employe)
                    <div class="mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">
                                Horaires de {{ $employe->first_name }} {{ $employe->last_name }}
                            </h2>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.horaires.create', ['employe_id' => $employe->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ajouter un horaire
                                </a>
                                <form action="{{ route('admin.horaires.standards') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employe_id" value="{{ $employe->id }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Créer horaires standards
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des horaires -->
                    <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Jour</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Heure de début</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Heure de fin</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Statut</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $joursSemaine = [
                                        0 => 'Dimanche',
                                        1 => 'Lundi',
                                        2 => 'Mardi',
                                        3 => 'Mercredi',
                                        4 => 'Jeudi',
                                        5 => 'Vendredi',
                                        6 => 'Samedi',
                                    ];
                                @endphp
                                
                                @forelse($horaires as $horaire)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $joursSemaine[$horaire->jour_semaine] ?? 'Inconnu' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $horaire->heure_debut }}</td>
                                        <td class="px-6 py-4">{{ $horaire->heure_fin }}</td>
                                        <td class="px-6 py-4">
                                            @if($horaire->actif)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Actif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.horaires.edit', $horaire) }}" class="text-blue-600 hover:text-blue-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.horaires.destroy', $horaire) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?');">
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
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p>Aucun horaire configuré pour cet employé</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
