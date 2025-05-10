<x-app-layout>
    <!-- Ajout de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Carte résumé -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Vue d'ensemble</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Employés -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-blue-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Employés</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $employeeCount }}</p>
                        </div>

                        <!-- Départements -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-green-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Départements</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $departmentCount }}</p>
                        </div>

                        <!-- Congés en attente -->
                        <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-purple-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Congés en attente</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingLeaveCount }}</p>
                        </div>

                        <!-- En congé -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-yellow-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">En congé actuel</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $onLeaveCount }}</p>
                        </div>

                        <!-- Absences non justifiées -->
                        <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-red-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Absences non justifiées</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Absence::where('statut', 'non_justifiée')->count() }}</p>
                        </div>

                        <!-- Pointages aujourd'hui -->
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-indigo-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Pointages aujourd'hui</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Pointage::whereDate('created_at', \Carbon\Carbon::today())->count() }}</p>
                        </div>
                        
                        <!-- Équipes -->
                        <div class="bg-teal-50 rounded-lg p-4 border border-teal-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-teal-100 rounded-lg mr-2">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700">Équipes</h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $equipeCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Graphique répartition des employés par département -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition des employés par département</h2>
                    <div class="h-64">
                        <canvas id="departmentsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graphiques d'analyse -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Graphique des absences par mois -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Absences par mois</h2>
                    <div class="h-64">
                        <canvas id="absencesChart"></canvas>
                    </div>
                </div>

                <!-- Graphique des pointages et retards -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Pointages et retards (7 derniers jours)</h2>
                    <div class="h-64">
                        <canvas id="pointagesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Actions principales</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Add Employee Button -->
                        <a href="{{ auth()->user()->role === 'Admin' ? route('Admin.employes.create') : route('Manager.employes.create') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-blue-50 transition-colors">
                            <div class="p-3 bg-blue-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Ajouter Employé</h3>
                                <p class="text-sm text-gray-500">Nouvelle fiche employé</p>
                            </div>
                        </a>

                        <!-- List Employees Button -->
                        <a href="{{ auth()->user()->role === 'Admin' ? route('Admin.employes.index') : route('Manager.employes.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-green-50 transition-colors">
                            <div class="p-3 bg-green-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Liste des Employés</h3>
                                <p class="text-sm text-gray-500">Voir tous les employés</p>
                            </div>
                        </a>

                        <!-- Leave Management Button -->
                        <a href="{{ route('Admin.conges.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-purple-50 transition-colors">
                            <div class="p-3 bg-purple-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Gestion des Congés</h3>
                                <p class="text-sm text-gray-500">Voir les demandes</p>
                            </div>
                        </a>


                        
                        <!-- Absences Management Button -->
                        <a href="{{ route('admin.absences.dashboard') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-red-50 transition-colors">
                            <div class="p-3 bg-red-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Gestion des Absences</h3>
                                <p class="text-sm text-gray-500">Tableau de bord et statistiques</p>
                            </div>
                        </a>
                        
                        <!-- Holidays Management Button -->
                        <a href="{{ route('admin.jours-feries.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-blue-50 transition-colors">
                            <div class="p-3 bg-blue-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Jours Fériés</h3>
                                <p class="text-sm text-gray-500">Gérer les jours fériés</p>
                            </div>
                        </a>
                        
                        <!-- Work Schedules Management Button -->
                        <a href="{{ route('admin.horaires.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-green-50 transition-colors">
                            <div class="p-3 bg-green-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Horaires Personnalisés</h3>
                                <p class="text-sm text-gray-500">Gérer les horaires des employés</p>
                            </div>
                        </a>
                        
                        <!-- Teams Management Button -->
                        <a href="{{ route('equipes.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-teal-50 transition-colors">
                            <div class="p-3 bg-teal-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Gestion des Équipes</h3>
                                <p class="text-sm text-gray-500">Créer et gérer les équipes</p>
                            </div>
                        </a>
                        
                        <!-- Team Schedules Management Button -->
                        <a href="{{ route('admin.emplois-du-temps-equipes') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-cyan-50 transition-colors">
                            <div class="p-3 bg-cyan-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Emplois du temps d'équipe</h3>
                                <p class="text-sm text-gray-500">Planifier les activités des équipes</p>
                            </div>
                        </a>

                        <!-- Workstations Management Button -->
                        <a href="{{ route('admin.postes-travail.index') }}" 
                           class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-indigo-50 transition-colors">
                            <div class="p-3 bg-indigo-100 rounded-full mr-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Postes de Travail</h3>
                                <p class="text-sm text-gray-500">Gérer les postes de travail</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Pointage Activities Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Activités récentes de pointage</h2>
                        <a href="{{ route('admin.pointages') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tous les pointages →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employé</th>
                                    <th scope="col" class="px-6 py-3">Date & Heure</th>
                                    <th scope="col" class="px-6 py-3">Poste de travail</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $recentPointages = \App\Models\Pointage::with(['employe', 'posteTravail'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                                @endphp
                                
                                @foreach ($recentPointages as $pointage)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($pointage->employe->photo)
                                            <img class="w-10 h-10 rounded-full mr-3" src="{{ asset('storage/' . $pointage->employe->photo) }}" alt="{{ $pointage->employe->first_name }}">
                                            @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $pointage->employe->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $pointage->employe->departement->name ?? 'Non assigné' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900">{{ $pointage->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $pointage->created_at->format('H:i:s') }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($pointage->posteTravail)
                                            <div class="font-medium text-gray-900">{{ $pointage->posteTravail->nom }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $pointage->posteTravail->adresse_mac }}</div>
                                        @else
                                            <span class="text-xs text-gray-500">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pointage->validé)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Validé</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                
                                @if($recentPointages->isEmpty())
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p>Aucun pointage récent trouvé</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Teams Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Équipes récentes</h2>
                        <a href="{{ route('equipes.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir toutes les équipes →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse ($recentEquipes as $equipe)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $equipe->nom }}</h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">
                                            {{ $equipe->employes->count() }} membres
                                        </span>
                                    </div>
                                    
                                    @if($equipe->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $equipe->description }}</p>
                                    @endif
                                    
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Responsable:</p>
                                        @if($equipe->responsable)
                                            <div class="flex items-center">
                                                @if($equipe->responsable->photo)
                                                    <img src="{{ asset('storage/' . $equipe->responsable->photo) }}" alt="{{ $equipe->responsable->first_name }}" class="h-8 w-8 rounded-full object-cover mr-2">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                                        <span class="text-xs text-gray-600">{{ substr($equipe->responsable->first_name, 0, 1) }}{{ substr($equipe->responsable->last_name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <span class="text-sm text-gray-800">{{ $equipe->responsable->first_name }} {{ $equipe->responsable->last_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    
                                    @if($equipe->employes->count() > 0)
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 mb-2">Membres:</p>
                                            <div class="flex -space-x-2 overflow-hidden">
                                                @foreach($equipe->employes->take(5) as $employe)
                                                    @if($employe->photo)
                                                        <img src="{{ asset('storage/' . $employe->photo) }}" alt="{{ $employe->first_name }}" class="h-8 w-8 rounded-full border-2 border-white object-cover">
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center">
                                                            <span class="text-xs text-gray-600">{{ substr($employe->first_name, 0, 1) }}{{ substr($employe->last_name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                
                                                @if($equipe->employes->count() > 5)
                                                    <div class="h-8 w-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center">
                                                        <span class="text-xs text-gray-600">+{{ $equipe->employes->count() - 5 }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                        <a href="{{ route('equipes.show', $equipe) }}" class="text-sm text-blue-600 hover:text-blue-800">Voir détails →</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 bg-gray-50 rounded-lg p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-600 mb-2">Aucune équipe n'a encore été créée</p>
                                <a href="{{ route('equipes.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Créer une équipe
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Leave Requests Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Demandes récentes de congés</h2>
                        <a href="{{ route('Admin.conges.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir toutes les demandes →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employé</th>
                                    <th scope="col" class="px-6 py-3">Période</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentConges as $conge)
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
                                                <div class="font-medium text-gray-900">{{ $conge->employee->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $conge->employee->position }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900">{{ $conge->start_date->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">au {{ $conge->end_date->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $conge->type }}</td>
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
                                        <div class="inline-flex space-x-2">
                                            <form method="POST" action="{{ route('Admin.conges.update', $conge->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" name="status" value="Approuvé" 
                                                        class="px-3 py-1 text-sm bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition-colors">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('Admin.conges.update', $conge->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" name="status" value="Rejeté" 
                                                        class="px-3 py-1 text-sm bg-red-50 text-red-700 rounded-md hover:bg-red-100 transition-colors">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Rejeter
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <span class="text-gray-400 text-sm">Traité</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Employees Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Nouveaux employés</h2>
                        <a href="{{ auth()->user()->role === 'Admin' ? route('Admin.employes.index') : route('Manager.employes.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tous les employés →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($recentEmployees as $employee)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                @if($employee->photo)
                                <img class="w-16 h-16 rounded-full" src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->full_name }}">
                                @else
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $employee->full_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $employee->position }}</p>
                                    <p class="text-xs text-gray-400">Depuis {{ $employee->hire_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Departments Overview Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Structure Organisationnelle</h2>
                <p class="text-sm text-gray-500 mt-1">Répartition des équipes par département</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="toggleDepartmentModal()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Département
                </button>
            </div>
        </div>

        <!-- Department Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-800">Départements Actifs</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $departements->count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-800">Membres Moyens</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($departements->avg('employes_count'), 1) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-800">Nouveaux Ce Mois</p>
                    <p class="text-2xl font-bold text-purple-600">+2</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Interactive Department List -->
        <div class="border rounded-lg overflow-hidden">
            <div class="grid grid-cols-1 divide-y">
                @foreach($departements as $departement)
                <div class="group hover:bg-gray-50 transition-colors p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $departement->name }}</h3>
                            <div class="flex items-center space-x-3 text-sm text-gray-500">
                                <span>{{ $departement->employes_count }} membres</span>
                                <span class="text-gray-300">•</span>
                                <span>Créé le {{ $departement->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-blue-600"
                                onclick="openDepartmentMenu('{{ $departement->id }}')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <!-- Context Menu (Hidden by default) -->
                        <div id="menu-{{ $departement->id }}" class="hidden absolute right-4 mt-8 bg-white shadow-lg rounded-lg border p-2">
                            <a href="{{ route('Admin.departements.edit', $departement) }}" 
                               class="flex items-center px-4 py-2 text-sm hover:bg-gray-50 rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Modifier
                            </a>
                            <form action="{{ route('Admin.departements.destroy', $departement) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center px-4 py-2 text-sm hover:bg-gray-50 rounded-md text-red-600"
                                        onclick="return confirm('Êtes-vous sûr ?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Empty State -->
        @if($departements->isEmpty())
        <div class="text-center py-12">
            <div class="mb-4 text-gray-400">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Aucun département enregistré</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier département</p>
            <div class="mt-6">
                <button onclick="toggleDepartmentModal()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Créer Département
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function toggleDepartmentModal() {
        const modal = document.getElementById('department-modal');
        modal.classList.toggle('hidden');
    }


    function openDepartmentMenu(depId) {
        const menu = document.getElementById(`menu-${depId}`);
        menu.classList.toggle('hidden');
        // Fermer les autres menus ouverts
        document.querySelectorAll('[id^="menu-"]').forEach(otherMenu => {
            if(otherMenu.id !== `menu-${depId}`) otherMenu.classList.add('hidden');
        });
    }

    // Fermer les menus en cliquant ailleurs
    document.addEventListener('click', (e) => {
        if(!e.target.closest('[onclick^="openDepartmentMenu"]')) {
            document.querySelectorAll('[id^="menu-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
</script>
        </div>
    </div>
    <!-- Modal de création -->
<div id="department-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Créer un nouveau département</h3>
            <button onclick="toggleDepartmentModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linepasswordjoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('Admin.departements.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du département</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" 
                        class="w-full mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Créer le département
                </button>
            </div>
        </form>
    </div>
</div>
    <!-- Scripts pour les graphiques -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique de répartition des départements
            const depCtx = document.getElementById('departmentsChart').getContext('2d');
            
            const departmentsChart = new Chart(depCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($departementNames) !!},
                    datasets: [{
                        data: {!! json_encode($departementCounts) !!},
                        backgroundColor: [
                            '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
            
            // Graphique des absences par mois
            const absencesCtx = document.getElementById('absencesChart').getContext('2d');
            
            const absencesChart = new Chart(absencesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [
                        {
                            label: 'Non justifiées',
                            data: {!! json_encode($unjustifiedData) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Justifiées',
                            data: {!! json_encode($justifiedData) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'En attente',
                            data: {!! json_encode($pendingData) !!},
                            backgroundColor: 'rgba(245, 158, 11, 0.7)',
                            borderColor: 'rgba(245, 158, 11, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rejetées',
                            data: {!! json_encode($rejectedData) !!},
                            backgroundColor: 'rgba(79, 70, 229, 0.7)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Graphique des pointages et retards
            const pointagesCtx = document.getElementById('pointagesChart').getContext('2d');
            
            // Récupérer les données des pointages des 7 derniers jours
            @php
            $days = [];
            $pointagesData = [];
            $retardsData = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $day = \Carbon\Carbon::now()->subDays($i);
                $days[] = $day->format('d M');
                
                $pointagesData[] = \App\Models\Pointage::whereDate('created_at', $day->format('Y-m-d'))
                    ->count();
                    
                $retardsData[] = \App\Models\Pointage::whereDate('created_at', $day->format('Y-m-d'))
                    ->where('retard_minutes', '>', 0)
                    ->count();
            }
            @endphp
            
            const pointagesChart = new Chart(pointagesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($days) !!},
                    datasets: [
                        {
                            label: 'Pointages',
                            data: {!! json_encode($pointagesData) !!},
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Retards',
                            data: {!! json_encode($retardsData) !!},
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>