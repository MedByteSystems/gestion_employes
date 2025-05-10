<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <!-- Header avec recherche et filtres -->
                <div class="flex flex-col md:flex-row gap-6 justify-between items-start md:items-center mb-8">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold text-gray-900">Gestion des Employés</h2>
                        <p class="text-gray-500">Total : {{ $employes->total() }} employés</p>
                    </div>
                    
                    <div class="w-full md:w-auto space-y-4">
                        <!-- Barre de recherche -->
                        <form method="GET" class="relative">
                            <input 
                                type="text" 
                                name="search"
                                placeholder="Rechercher un employé..." 
                                value="{{ request('search') }}"
                                class="w-full md:w-80 pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-all"
                            >
                            <div class="absolute left-4 top-3.5">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </form>

                        <!-- Filtres -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <select 
                                name="department" 
                                class="select-filter px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Tous les départements</option>
                                @foreach($departements as $departement)
                                    <option value="{{ $departement->id }}" {{ request('department') == $departement->id ? 'selected' : '' }}>
                                        {{ $departement->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select 
                                name="status" 
                                class="select-filter px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tableau des employés -->
                <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Collaborateur</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Poste</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Département</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Contrat</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($employes as $employe)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($employe->photo)
                                        <img class="w-10 h-10 rounded-full mr-3" src="{{ asset('storage/' . $employe->photo) }}" alt="{{ $employe->first_name }}">
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        @endif
                                        <div>
                                            {{ $employe->first_name }} {{ $employe->last_name }}
                                            <div class="text-sm text-gray-500">{{ $employe->cin }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $employe->position }}</td>
                                <td class="px-6 py-4">{{ $employe->departement->name }}</td>
                                <td class="px-6 py-4">{{ $employe->hire_date->format('d/m/Y') }}</td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded-full">Actif</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ auth()->user()->role === 'Admin' 
                                            ? route('Admin.employes.edit', $employe->id) 
                                            : route('Manager.employes.edit', $employe->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ auth()->user()->role === 'Admin' 
                                            ? route('Admin.employes.destroy', $employe->id) 
                                            : route('Manager.employes.destroy', $employe->id) }}" 
                                  method="POST">                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete(this.closest('form'))" 
                                                    class="text-red-600 hover:text-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <p>Aucun employé trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $employes->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JavaScript -->
    <script>
        // Gestion dynamique des filtres
        document.querySelectorAll('.select-filter').forEach(select => {
            select.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set(this.name, this.value);
                window.location.href = url.toString();
            });
        });

        // SweetAlert2 confirmation modal
        function confirmDelete(form) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>