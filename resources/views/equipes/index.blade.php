<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Équipes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Liste des équipes</h3>
                        <a href="{{ route('equipes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Créer une nouvelle équipe
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de membres</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($equipes as $equipe)
                                    <tr>
                                        <td class="py-2 px-4 whitespace-nowrap">{{ $equipe->nom }}</td>
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @if ($equipe->responsable)
                                                {{ $equipe->responsable->first_name }} {{ $equipe->responsable->last_name }}
                                            @else
                                                <span class="text-gray-400">Non assigné</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 whitespace-nowrap">{{ $equipe->employes->count() }}</td>
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('equipes.show', $equipe) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                                <a href="{{ route('equipes.edit', $equipe) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                                <form action="{{ route('equipes.destroy', $equipe) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">Aucune équipe trouvée</td>
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
