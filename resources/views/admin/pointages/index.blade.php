<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Suivi des Présences</h1>
                <div class="text-sm text-gray-500">Mois en cours : {{ \Carbon\Carbon::now()->format('F Y') }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden ring-1 ring-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                            <tr>
                                <th class="px-8 py-6 text-left font-semibold uppercase tracking-wider">Collaborateur</th>
                                <th class="px-6 py-4 text-center">Statut du jour</th>
                                <th class="px-6 py-4 text-center">Retard cumulé</th>
                                <th class="px-6 py-4 text-center">Absences/mois</th>
                                <th class="px-6 py-4 text-center">Pointages</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($employes as $employe)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        @if($employe->photo)
                                            <img class="h-10 w-10 rounded-full mr-4 object-cover" src="{{ asset('storage/' . $employe->photo) }}" alt="{{ $employe->full_name }}">
                                        @else
                                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                                <span class="text-blue-800 font-medium">
                                                    @php
                                                        // Fonction pour générer les initiales
                                                        $initials = implode('', array_map(function($n) {
                                                            return strtoupper($n[0]);
                                                        }, explode(' ', $employe->full_name)));
                                                        echo substr($initials, 0, 2);
                                                    @endphp
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $employe->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $employe->departement ? $employe->departement->name : 'Non assigné' }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Statut du jour -->
                                <td class="px-6 py-5 text-center">
                                    @php
                                        // Déterminer le statut du jour en fonction des pointages
                                        $today = \Carbon\Carbon::today();
                                        $todayPointage = $employe->pointages()->whereDate('created_at', $today)->first();
                                        
                                        if ($todayPointage) {
                                            if ($todayPointage->retard_minutes > 0) {
                                                $status = 'Retard';
                                                $statusClass = 'bg-amber-100 text-amber-800';
                                            } else {
                                                $status = 'À l\'heure';
                                                $statusClass = 'bg-green-100 text-green-800';
                                            }
                                        } else {
                                            $status = 'Absent';
                                            $statusClass = 'bg-red-100 text-red-800';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <!-- Retard cumulé -->
                                <td class="px-6 py-5 text-center text-gray-700 font-medium">
                                    @php
                                        $monthStart = \Carbon\Carbon::now()->startOfMonth();
                                        $monthEnd = \Carbon\Carbon::now()->endOfMonth();
                                        $totalRetard = $employe->pointages()
                                            ->whereBetween('created_at', [$monthStart, $monthEnd])
                                            ->sum('retard_minutes');
                                    @endphp
                                    {{ $totalRetard }} min
                                </td>

                                <!-- Absences/mois -->
                                <td class="px-6 py-5 text-center">
                                    @php
                                        // Compter les absences où la période d'absence chevauche le mois en cours
                                        $absencesCount = \App\Models\Absence::where('employe_id', $employe->id)
                                            ->where(function($query) use ($monthStart, $monthEnd) {
                                                $query->whereBetween('date_debut', [$monthStart, $monthEnd]) // date_debut dans le mois
                                                    ->orWhereBetween('date_fin', [$monthStart, $monthEnd])  // date_fin dans le mois
                                                    ->orWhere(function($q) use ($monthStart, $monthEnd) {
                                                        $q->where('date_debut', '<', $monthStart)
                                                          ->where('date_fin', '>', $monthEnd);  // période englobant le mois
                                                    });
                                            })
                                            ->count();
                                    @endphp
                                    <span class="text-red-600 font-semibold">{{ $absencesCount }} jour(s)</span>
                                </td>

                                <!-- Pointages -->
                                <td class="px-6 py-5 text-center text-blue-600 font-semibold">
                                    {{ $employe->pointages()->whereBetween('created_at', [$monthStart, $monthEnd])->count() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Légende -->
                <div class="border-t border-gray-100 px-8 py-4 bg-gray-50">
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <div>Légende :</div>
                        <div class="space-x-4">
                            <span class="inline-flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>À l'heure
                            </span>
                            <span class="inline-flex items-center">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>Retard
                            </span>
                            <span class="inline-flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>Absent
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>