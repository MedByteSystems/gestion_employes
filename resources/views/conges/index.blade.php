<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Demandes de congés</h1>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employé</th>
                                    <th scope="col" class="px-6 py-3">Période</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Motif</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conges as $conge)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($conge->employee->photo)
                                            <img class="w-10 h-10 rounded-full mr-3" src="{{ asset('storage/' . $conge->employee->photo) }}" alt="{{ $conge->employee->first_name }}">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $conge->employee->last_name }} {{ $conge->employee->first_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $conge->employee->position }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900">{{ $conge->start_date->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">au {{ $conge->end_date->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $conge->type }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate">{{ $conge->reason }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($conge->status === 'Approuvé') bg-green-100 text-green-800
                                            @elseif($conge->status === 'Rejeté') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $conge->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($conge->status === 'En attente')
                                        <form method="POST" 
                                        action="{{ auth()->user()->role === 'Admin' 
                                            ? route('Admin.conges.update', $conge->id) 
                                            : route('Manager.conges.update', $conge->id) }}">                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="status" value="Approuvé" 
                                                    class="px-3 py-1 text-sm bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approuver
                                            </button>
                                            <button type="submit" name="status" value="Rejeté" 
                                                    class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition-colors">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Rejeter
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-sm">Traité</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $conges->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>