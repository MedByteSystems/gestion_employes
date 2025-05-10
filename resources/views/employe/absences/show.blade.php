<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('Employé.absences.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la liste des absences
                </a>
            </div>

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Détails de l'absence</h1>
                    
                    <div>
                        @if($absence->statut === 'justifiée')
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">Justifiée</span>
                        @elseif($absence->statut === 'en_attente')
                            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">En attente</span>
                        @elseif($absence->statut === 'rejetée')
                            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">Rejetée</span>
                        @else
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium">Non justifiée</span>
                        @endif
                    </div>
                </div>

                <!-- Informations sur l'absence -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Période d'absence</h3>
                        <p class="text-gray-700">
                            @if($absence->date_debut->format('Y-m-d') == $absence->date_fin->format('Y-m-d'))
                                {{ $absence->date_debut->format('d/m/Y') }}
                            @else
                                Du {{ $absence->date_debut->format('d/m/Y') }} au {{ $absence->date_fin->format('d/m/Y') }}
                            @endif
                            <span class="text-sm text-gray-500 ml-2">({{ $absence->dureeJours() }} jour(s))</span>
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Motif</h3>
                        <p class="text-gray-700">{{ $absence->motif ?: 'Non spécifié' }}</p>
                    </div>
                </div>
                
                <!-- Justification -->
                <div class="mb-8">
                    <h3 class="font-medium text-gray-800 mb-2">Justification</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $absence->justification ?: 'Aucune justification fournie.' }}</p>
                    </div>
                </div>
                
                <!-- Document justificatif -->
                @if($absence->document_path)
                    <div class="mb-8">
                        <h3 class="font-medium text-gray-800 mb-2">Document justificatif</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <a href="{{ Storage::url($absence->document_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Voir le document
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Commentaire de l'administrateur -->
                @if($absence->commentaire_admin)
                    <div class="mb-8">
                        <h3 class="font-medium text-gray-800 mb-2">Commentaire de l'administrateur</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-line">{{ $absence->commentaire_admin }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Actions -->
                @if($absence->statut === 'non_justifiée')
                    <div class="mt-8">
                        <a href="{{ route('Employé.absences.create', ['date' => $absence->date_debut->format('Y-m-d')]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Justifier cette absence
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
