<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête de pointage -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Enregistrement des Temps de Travail</h1>
                <div class="flex items-center justify-center space-x-4">
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-full">
                        <span class="text-sm text-gray-600">{{ now()->format('d F Y') }}</span>
                    </div>
                    <div class="px-4 py-2 bg-blue-100 rounded-full">
                        <span class="text-sm font-medium text-blue-700">Horaires Standard : 08h30 - 17h30</span>
                    </div>
                </div>
            </div>

            <!-- Widget de pointage principal -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 ring-1 ring-gray-100">
                <div class="flex flex-col items-center justify-center space-y-6">
                    <!-- Cercle de progression -->
                    <div class="relative w-48 h-48">
                        <svg class="transform -rotate-90" width="100%" height="100%" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" class="stroke-current text-gray-200" stroke-width="8" fill="none"/>
                            <circle cx="50" cy="50" r="45" class="stroke-current text-blue-600" stroke-width="8" fill="none"
                                    stroke-dasharray="283"
                                    stroke-dashoffset="283"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button id="main-check-btn" class="px-8 py-4 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors text-lg font-semibold">
                                Check-in
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statut actuel -->
                    <div class="text-center">
                        <p class="text-xl font-semibold text-gray-700 mb-2">Temps travaillé aujourd'hui</p>
                        <div id="worked-time" class="text-4xl font-bold text-gray-900">00h 00min</div>
                    </div>
                </div>
            </div>

            <!-- Section Check-in/out rapide -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Enregistrement Rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button id="checkin-btn" class="p-4 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors">
                        <span class="block text-xl font-bold">Check-in</span>
                        <span class="text-sm" id="checkin-time">--:--</span>
                    </button>
                    <button id="checkout-btn" class="p-4 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors">
                        <span class="block text-xl font-bold">Check-out</span>
                        <span class="text-sm" id="checkout-time">--:--</span>
                    </button>
                </div>
            </div>

            <!-- Historique des pointages -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Historique des Pointages</h2>
                <div class="space-y-4" id="time-entries">
                    <!-- Entrées dynamiques seront ajoutées ici -->
                </div>

                <!-- Résumé mensuel -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600"><span id="month-total">0</span>h</div>
                            <div class="text-sm text-gray-500">Total ce mois</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-amber-600"><span id="month-delay">0</span>min</div>
                            <div class="text-sm text-gray-500">Retard cumulé</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600"><span id="month-absence">0</span></div>
                            <div class="text-sm text-gray-500">Absences</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // État du pointage
        let isCheckedIn = false;
        let checkinTime = null;
        let totalWorked = 0;

        // Formatage de l'heure
        const formatTime = (date) => {
            return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        };

        // Mise à jour de l'affichage
        const updateDisplay = () => {
            const now = new Date();
            
            if(isCheckedIn) {
                document.getElementById('main-check-btn').textContent = 'Check-out';
                document.getElementById('main-check-btn').classList.replace('bg-blue-600', 'bg-red-600');
                document.getElementById('main-check-btn').classList.replace('hover:bg-blue-700', 'hover:bg-red-700');
                
                // Calcul du temps travaillé
                const diff = Math.floor((now - checkinTime) / 1000 / 60); // en minutes
                const hours = Math.floor(diff / 60);
                const minutes = diff % 60;
                document.getElementById('worked-time').textContent = 
                    `${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}min`;
            } else {
                document.getElementById('main-check-btn').textContent = 'Check-in';
                document.getElementById('main-check-btn').classList.replace('bg-red-600', 'bg-blue-600');
                document.getElementById('main-check-btn').classList.replace('hover:bg-red-700', 'hover:bg-blue-700');
            }
        };

        // Gestionnaire principal de pointage
        document.getElementById('main-check-btn').addEventListener('click', () => {
            const now = new Date();
            
            if(!isCheckedIn) {
                // Check-in
                isCheckedIn = true;
                checkinTime = now;
                document.getElementById('checkin-time').textContent = formatTime(now);
            } else {
                // Check-out
                isCheckedIn = false;
                document.getElementById('checkout-time').textContent = formatTime(now);
                
                // Ajout à l'historique
                const entry = document.createElement('div');
                entry.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg';
                entry.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${now.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' })}</div>
                            <div class="text-sm text-gray-500">Journée complète</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">${document.getElementById('worked-time').textContent}</div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">À l'heure</span>
                    </div>
                `;
                document.getElementById('time-entries').prepend(entry);
                
                // Mise à jour des totaux
                const diff = Math.floor((now - checkinTime) / 1000 / 60);
                totalWorked += diff;
                document.getElementById('month-total').textContent = Math.floor(totalWorked / 60);
            }
            
            updateDisplay();
        });

        // Mise à jour en temps réel
        setInterval(() => {
            if(isCheckedIn) updateDisplay();
        }, 1000);
    </script>
</x-app-layout>