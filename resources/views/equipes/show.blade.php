<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de l\'équipe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('equipes.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Retour à la liste des équipes
                        </a>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $equipe->nom }}</h3>
                        @if ($equipe->description)
                            <p class="text-gray-600 mb-4">{{ $equipe->description }}</p>
                        @endif

                        <!-- Section Emploi du temps PDF -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-semibold mb-3">Emploi du temps</h4>
                            
                            @if($equipe->emploi_du_temps_pdf)
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h-6l2 3-2 3h6l-2-3 2-3z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium">{{ $equipe->emploi_du_temps_nom }}</p>
                                            <p class="text-sm text-gray-500">Emploi du temps au format PDF</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('equipes.download-pdf', $equipe) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger
                                        </a>
                                        <a href="{{ route('equipes.upload-pdf-form', $equipe) }}" class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Remplacer
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Aucun emploi du temps n'a été téléversé pour cette équipe.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('equipes.upload-pdf-form', $equipe) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Téléverser un emploi du temps
                                </a>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h4 class="text-lg font-semibold mb-2">Responsable</h4>
                            @if ($equipe->responsable)
                                <div class="flex items-center">
                                    @if ($equipe->responsable->photo)
                                        <img src="{{ asset('storage/' . $equipe->responsable->photo) }}" alt="{{ $equipe->responsable->first_name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                            <span class="text-gray-600">{{ substr($equipe->responsable->first_name, 0, 1) }}{{ substr($equipe->responsable->last_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $equipe->responsable->first_name }} {{ $equipe->responsable->last_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $equipe->responsable->position }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">Aucun responsable assigné</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Membres de l'équipe ({{ $equipe->employes->count() }})</h4>
                        
                        @if ($equipe->employes->isEmpty())
                            <p class="text-gray-500">Aucun membre dans cette équipe</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($equipe->employes as $employe)
                                    <div class="border rounded-lg p-4 flex items-start">
                                        @if ($employe->photo)
                                            <img src="{{ asset('storage/' . $employe->photo) }}" alt="{{ $employe->first_name }}" class="h-12 w-12 rounded-full object-cover mr-3">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                <span class="text-gray-600">{{ substr($employe->first_name, 0, 1) }}{{ substr($employe->last_name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium">{{ $employe->first_name }} {{ $employe->last_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $employe->position }}</p>
                                            @if ($equipe->responsable && $employe->id === $equipe->responsable->id)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                    Responsable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <a href="{{ route('equipes.edit', $equipe) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                            Modifier
                        </a>
                        <form action="{{ route('equipes.destroy', $equipe) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
