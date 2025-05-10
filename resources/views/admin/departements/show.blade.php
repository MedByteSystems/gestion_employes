<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $departement->name }}</h2>
                            <p class="text-gray-600 mt-2">
                                {{ $departement->employes_count }} employé(s) dans ce département
                            </p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('Admin.departements.edit', $departement->id) }}" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Modifier
                            </a>
                            <a href="{{ route('Admin.dashboard') }}" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                Retour
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Employés du département</h3>
                        
                        @if($departement->employes->isEmpty())
                            <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-500">
                                Aucun employé dans ce département
                            </div>
                        @else
                            <div class="bg-white rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poste</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'embauche</th>
                                            <th class="px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($departement->employes as $employe)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $employe->first_name }} {{ $employe->last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $employe->position }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $employe->hire_date->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('Admin.employes.edit', $employe->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>