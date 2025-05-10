<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Téléverser un emploi du temps PDF') }} - {{ $equipe->nom }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('equipes.show', $equipe) }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Retour aux détails de l'équipe
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('equipes.upload-pdf', $equipe) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Téléversement d'un emploi du temps au format PDF</h3>
                            
                            @if($equipe->emploi_du_temps_pdf)
                                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                                    <p class="font-bold">Fichier actuel :</p>
                                    <p>{{ $equipe->emploi_du_temps_nom }}</p>
                                    <div class="mt-2">
                                        <a href="{{ route('equipes.download-pdf', $equipe) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Télécharger
                                        </a>
                                    </div>
                                    <p class="mt-4 text-sm">Le téléversement d'un nouveau fichier remplacera le fichier actuel.</p>
                                </div>
                            @endif
                            
                            <div class="mb-4">
                                <label for="emploi_du_temps_pdf" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fichier PDF (max. 10 Mo)
                                </label>
                                <input type="file" name="emploi_du_temps_pdf" id="emploi_du_temps_pdf" accept=".pdf" 
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                <p class="mt-1 text-sm text-gray-500">Sélectionnez un fichier PDF contenant l'emploi du temps de l'équipe.</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Téléverser le fichier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
