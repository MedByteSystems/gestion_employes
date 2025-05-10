<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord Employé</h1>
                    <p class="mt-2 text-gray-600">Bonjour {{ Auth::user()->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('Employé.pointage.index') }}" class="md:hidden px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors text-sm">
                        Pointage
                    </a>
                    <div class="flex items-center px-4 py-2 bg-green-100 rounded-full">
                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                        <span class="text-sm font-medium text-green-700">Actif</span>
                    </div>
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-full">
                        <span class="text-sm text-gray-600">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Cartes Informations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Carte Pointage -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-indigo-100">
                    <div class="h-full flex flex-col justify-between">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-indigo-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-500">Pointage</h3>
                                @if($dernierPointage ?? null)
                                    <p class="text-sm {{ $dernierPointage->statut === 'retard' ? 'text-yellow-500' : 'text-green-500' }}">
                                        Dernier pointage : {{ $dernierPointage->heure_reelle->format('H:i') }}
                                        @if($dernierPointage->statut === 'retard')
                                            <span class="text-yellow-500">({{ $dernierPointage->retard_minutes }} min de retard)</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400">Aucun pointage aujourd'hui</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('Employé.pointage.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Pointer ma présence
                            </a>
                            <a href="{{ route('Employé.pointage.historique') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-indigo-300 text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors text-sm">
                                Voir mon historique
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Carte Congés -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-500">Congés disponibles</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $availableLeaveDays ?? 'N/A' }} jours</p>
                        </div>
                    </div>
                </div>

                <!-- Demandes en Cours -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-purple-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-500">Demandes en attente</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingRequestsCount ?? '0' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Prochain Congé -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-500">Prochain congé</h3>
                            <p class="text-2xl font-bold text-gray-900">
                                @if($nextApprovedLeave)
                                    {{ $nextApprovedLeave->start_date->format('d/m') }} - {{ $nextApprovedLeave->end_date->format('d/m') }}
                                @else
                                    Aucun
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-100">
                    <div class="h-full flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-500 mb-4">Actions Rapides</h3>
                        </div>
                        <button onclick="openRequestModal()"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle Demande
                        </button>
                    </div>
                </div>

                <!-- Absences -->
                <div class="bg-white p-6 rounded-xl shadow-sm border {{ $absencesNonJustifiees > 0 ? 'border-red-300' : 'border-red-100' }}">
                    <div class="h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="p-3 {{ $absencesNonJustifiees > 0 ? 'bg-red-200' : 'bg-red-100' }} rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-500">Mes absences</h3>
                                    <p class="text-sm text-gray-500">Justifiez les absences détectées par le système</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                @php
                                    $absencesNonJustifiees = Auth::user()->employe->absences()->where('statut', 'non_justifiée')->count();
                                    $absencesEnAttente = Auth::user()->employe->absences()->where('statut', 'en_attente')->count();
                                @endphp
                                
                                @if($absencesNonJustifiees > 0)
                                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-bold">Action requise</p>
                                                <p>{{ $absencesNonJustifiees }} absence(s) à justifier</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($absencesEnAttente > 0)
                                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 rounded mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-bold">En cours de traitement</p>
                                                <p>{{ $absencesEnAttente }} justification(s) en attente d'approbation</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($absencesNonJustifiees == 0 && $absencesEnAttente == 0)
                                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <div>
                                                <p class="font-bold">Tout est à jour</p>
                                                <p>Aucune absence à justifier</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                            @if($absencesNonJustifiees > 0)
                                <a href="{{ route('Employé.absences.index', ['filtre' => 'non_justifiee']) }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Justifier mes absences
                                </a>
                            @else
                                <a href="{{ route('Employé.absences.index') }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Voir mes absences
                                </a>
                            @endif
                            <a href="{{ route('Employé.absences.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors text-sm">
                                Historique complet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Emplois du temps d'équipe -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-teal-100">
                    <div class="h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="p-3 bg-teal-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-500">Emplois du temps d'équipe</h3>
                                    <p class="text-sm text-gray-500">Consultez les plannings de vos équipes</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('Employé.emplois-du-temps') }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Voir mes plannings
                            </a>
                        </div>
                    </div>
                </div>
                
              

            <!-- Historique Pointage -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Activité Récente</h2>
                    <a href="{{ route('Employé.pointage.historique') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout l'historique
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($derniersPointages as $pointage)
                        <div class="flex items-center justify-between p-4 {{ $loop->first ? 'bg-blue-50 border border-blue-100' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 rounded-lg {{ $pointage->statut === 'à l\'heure' ? 'bg-green-100 text-green-600' : ($pointage->statut === 'retard' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                    @if($pointage->statut === 'à l\'heure')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @elseif($pointage->statut === 'retard')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $pointage->date->format('d/m/Y') }}</div>
                                    <div class="text-sm {{ $pointage->statut === 'à l\'heure' ? 'text-green-600' : ($pointage->statut === 'retard' ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ ucfirst($pointage->statut) }}
                                        @if($pointage->retard_minutes > 0)
                                            ({{ $pointage->retard_minutes }} min)
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">
                                {{ $pointage->heure_reelle->format('H:i') }}
                            </span>
                        </div>
                    @empty
                        <div class="flex items-center justify-center p-6 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500">Aucun pointage enregistré</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Modale Demande Congé -->
            <div id="request-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold">Nouvelle Demande</h3>
                        <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form method="POST" action="{{ route('Employé.conges.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                                <input type="date" name="start_date" required 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                                <input type="date" name="end_date" required 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type de congé</label>
                                <select name="type" class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="Annuel">Annuel</option>
                                    <option value="Maladie">Maladie</option>
                                    <option value="Maternité">Maternité</option>
                                    <option value="Sans solde">Sans solde</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Motif</label>
                                <textarea name="reason" rows="3" required 
                                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500"
                                          placeholder="Décrivez la raison de votre demande..."></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Soumettre la demande
                            </button>                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal() {
            document.getElementById('request-modal').classList.remove('hidden');
        }

        function closeRequestModal() {
            document.getElementById('request-modal').classList.add('hidden');
        }

        document.getElementById('request-modal').addEventListener('click', function(e) {
            if(e.target === this) closeRequestModal();
        });
    </script>
</x-app-layout>