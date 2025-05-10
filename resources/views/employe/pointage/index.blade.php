<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('Employé.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au tableau de bord
                </a>
            </div>

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Pointage Journalier</h1>
                        <p class="mt-2 text-gray-600">{{ now()->translatedFormat('l d F Y') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center">
                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="horloge" class="font-semibold">{{ now()->format('H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statut du pointage -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="flex items-center mb-4 md:mb-0">
                            @if($dernierPointage)
                                @if($dernierPointage->statut === 'à l\'heure')
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">Pointage effectué</h3>
                                        <p class="text-gray-600">Vous êtes arrivé à l'heure aujourd'hui</p>
                                    </div>
                                @elseif($dernierPointage->statut === 'retard')
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">Pointage effectué</h3>
                                        <p class="text-gray-600">Retard de {{ $dernierPointage->retard_minutes }} minutes</p>
                                    </div>
                                @endif
                            @else
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Pointage non effectué</h3>
                                    <p class="text-gray-600">Veuillez pointer votre présence</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="text-sm text-gray-500 mb-1">Horaires prévus</div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                    {{ $heurePrevue->format('H:i') }}
                                </span>
                                <span class="text-gray-400">-</span>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                    {{ $heureDepart->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de pointage -->
                @if(!$dernierPointage)
                <form action="{{ route('Employé.pointage.pointer') }}" method="POST" class="space-y-6" id="pointageForm" data-poste-autorise="{{ $posteAutorise ? 'true' : 'false' }}" onsubmit="console.log('Formulaire soumis avec device_id: ' + document.getElementById('device_id_form').value);">
                    @csrf
                    <input type="hidden" name="device_id" id="device_id_form">
                    <input type="hidden" name="poste_travail_id" value="{{ $posteSelectionne ? $posteSelectionne->id : '' }}">
                    <input type="hidden" name="type" value="entree">
                    
                    <!-- Informations sur le poste de travail -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h3 class="font-medium text-gray-800 mb-2">Statut de votre poste de travail</h3>
                        
                        <div class="mt-1 p-3 bg-gray-100 rounded-md">
                            <p class="text-sm text-gray-600">Le système vérifie automatiquement si ce poste est autorisé pour le pointage.</p>
                            <p class="text-sm text-gray-600 mt-2">Statut : 
                                @if($posteAutorise)
                                    <span class="font-medium text-green-600">✓ Poste de travail autorisé : {{ $posteSelectionne->nom }}</span>
                                @else
                                    <span class="font-medium text-red-600">✗ Poste de travail non autorisé</span>
                                @endif
                            </p>
                        </div>
                        @if(!$posteAutorise)
                            <p class="mt-3 text-sm text-gray-600">Pour autoriser ce poste de travail, demandez à votre administrateur d'enregistrer l'identifiant unique de cet appareil.</p>
                        @endif
                        
                        <div>
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-700 mb-2">Identifiez votre poste de travail</h5>
                                
                                <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <h6 class="text-sm font-medium text-blue-800 mb-2">Identifiant unique de cet appareil</h6>
                                    <div class="flex items-center mb-2">
                                        <p class="text-sm text-gray-600 mr-2">ID de l'appareil : <span class="font-mono font-medium" id="device-id"></span></p>
                                        <button type="button" id="copyDeviceIdBtn" class="bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium py-1 px-2 rounded inline-flex items-center" onclick="copyDeviceId()">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                            </svg>
                                            Copier
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500">Cet identifiant est unique à cet appareil et ne change pas entre les sessions ou navigateurs. Communiquez-le à votre administrateur pour enregistrer ce poste de travail.</p>
                                </div>
                                
                                <!-- Le formulaire de détection automatique a été supprimé pour éviter les rafraîchissements automatiques -->
                                
                                <p class="text-sm text-gray-600 mt-1">
                                    @if($posteAutorise)
                                        <span class="text-green-600 font-medium">✓ Poste de travail autorisé: {{ $posteSelectionne->nom }}</span>
                                    @else
                                        <span class="text-red-600 font-medium">✗ Poste de travail non autorisé</span>
                                    @endif
                                </p>
                                
                                @if(!$posteAutorise)
                                    <div class="mt-2 text-xs text-gray-500">
                                        <p>Pour autoriser ce poste, demandez à l'administrateur d'ajouter ce poste de travail à votre compte.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Localisation -->
                    <div>
                        <label for="localisation" class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                        <div class="flex items-center">
                            <input type="text" id="localisation" name="localisation" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Votre localisation actuelle">
                            <button type="button" id="getLocationBtn" class="ml-2 p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Cliquez sur l'icône pour utiliser votre position actuelle</p>
                    </div>
                    
                    <!-- Commentaire -->
                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                        <textarea id="commentaire" name="commentaire" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Ajoutez un commentaire si nécessaire..."></textarea>
                    </div>
                    
                    <button type="submit" id="btnPointer" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors {{ !$posteAutorise ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$posteAutorise ? 'disabled' : '' }}>
                        Pointer ma présence
                    </button>
                    @if(!$posteAutorise)
                        <p class="mt-2 text-sm text-center text-red-500">Le pointage n'est pas possible depuis ce poste de travail non autorisé</p>
                    @else
                        <p class="mt-2 text-sm text-center text-green-500" id="msgPointage">Vous pouvez pointer votre présence</p>
                    @endif
                    
                    <div id="heuresAlert" class="hidden mt-3 p-3 bg-red-100 text-red-700 rounded-lg">
                        Le pointage n'est autorisé qu'entre 7h00 et 18h00.
                    </div>
                </form>
                @else
                <div class="bg-gray-100 rounded-lg p-6 text-center">
                    <p class="text-gray-700">Vous avez déjà pointé aujourd'hui à {{ $dernierPointage->heure_reelle->format('H:i') }}</p>
                    <a href="{{ route('Employé.pointage.historique') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Voir mon historique
                    </a>
                </div>
                @endif
            </div>

            <!-- Information sur le système de pointage -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Comment fonctionne le pointage</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3 mt-1">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Vérification par poste de travail</h3>
                            <p class="text-gray-600 text-sm">Le système vérifie que vous utilisez bien votre poste de travail attribué pour pointer. Chaque employé ne peut pointer que depuis son ordinateur professionnel.</p>
                        </div>
                    </div>
                    

                    
                    <div class="flex items-start">
                        <div class="p-2 bg-yellow-100 rounded-lg mr-3 mt-1">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Horaires de pointage</h3>
                            <p class="text-gray-600 text-sm">Heure d'arrivée prévue: <span class="font-medium">{{ $heurePrevue->format('H:i') }}</span></p>
                            <p class="text-gray-600 text-sm">Heure de départ prévue: <span class="font-medium">{{ $heureDepart->format('H:i') }}</span></p>
                        </div>
                    </div>
                    
                    @if($postesTravail->count() == 0)
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="p-2 bg-red-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-red-800">Aucun poste de travail autorisé</h3>
                                <p class="text-red-700 text-sm">Vous n'avez aucun poste de travail enregistré à votre nom. Veuillez contacter votre administrateur système pour qu'il enregistre votre poste de travail (ID de l'appareil : <span id="device-id-error"></span>)).</p>
                            </div>
                        </div>
                    </div>
                    @elseif(!$posteAutorise)
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-yellow-800">Poste de travail non reconnu automatiquement</h3>
                                <p class="text-yellow-700 text-sm">L'identifiant de votre appareil (<span id="device-id-warning"></span>) n'a pas été reconnu automatiquement. Veuillez sélectionner manuellement votre poste de travail dans la liste ci-dessus.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

<script>
// Génère un identifiant unique pour cet appareil
function generateDeviceId() {
    // Vérifier si un identifiant existe déjà dans le localStorage
    let deviceId = localStorage.getItem('deviceId');
    
    // Si aucun identifiant n'existe, en générer un nouveau
    if (!deviceId) {
        // Collecter des informations sur l'appareil qui sont relativement stables
        const screenInfo = `${screen.width}x${screen.height}x${screen.colorDepth}`;
        const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const language = navigator.language;
        const platform = navigator.platform;
        const userAgent = navigator.userAgent;
        
        // Créer une empreinte unique en combinant ces informations
        const deviceInfo = `${screenInfo}|${timeZone}|${language}|${platform}|${userAgent}|${Date.now()}`;
        
        // Générer un hash à partir de l'empreinte
        deviceId = btoa(deviceInfo).replace(/[^a-zA-Z0-9]/g, '').substr(0, 32);
        
        // Stocker l'identifiant dans le localStorage
        localStorage.setItem('deviceId', deviceId);
    }
    
    return deviceId;
}

// Générer l'identifiant de l'appareil au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const deviceId = generateDeviceId();
    
    // Afficher l'identifiant de l'appareil pour référence
    if (document.getElementById('device-id')) {
        document.getElementById('device-id').textContent = deviceId;
    }
    
    // Remplir les éléments d'erreur et d'avertissement avec l'ID de l'appareil
    if (document.getElementById('device-id-error')) {
        document.getElementById('device-id-error').textContent = deviceId;
    }
    
    // Stocker le device_id dans un cookie pour qu'il soit transmis automatiquement au serveur
    document.cookie = `device_id=${deviceId}; path=/; max-age=${60*60*24*365}`; // Valide pour 1 an
    
    // Si l'URL ne contient pas déjà le device_id, recharger la page avec le device_id dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('device_id')) {
        urlParams.set('device_id', deviceId);
        // Recharger la page avec le device_id dans l'URL
        window.location.search = urlParams.toString();
    }
    
    // Demander automatiquement l'autorisation de géolocalisation au chargement de la page
    // Mais seulement si le poste est autorisé (pour éviter de demander inutilement l'autorisation)
    const posteAutorise = document.querySelector('[data-poste-autorise="true"]');
    if (posteAutorise && navigator.geolocation) {
        setTimeout(function() {
            try {
                navigator.geolocation.getCurrentPosition(
                    // Succès
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        const localisationInput = document.getElementById('localisation');
                        
                        if (localisationInput) {
                            // Formater les coordonnées pour le champ de saisie
                            localisationInput.value = `${latitude}, ${longitude}`;
                            console.log('Géolocalisation automatique réussie:', localisationInput.value);
                        }
                    },
                    // Erreur
                    function(error) {
                        console.error('Erreur de géolocalisation automatique:', error);
                    },
                    // Options
                    { 
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0 
                    }
                );
            } catch (e) {
                console.error('Erreur lors de la géolocalisation automatique:', e);
            }
        }, 1000); // Délai d'une seconde pour laisser la page se charger complètement
    }
    
    if (document.getElementById('device-id-warning')) {
        document.getElementById('device-id-warning').textContent = deviceId;
    }
    
    // Remplir le champ caché du formulaire de pointage
    if (document.getElementById('device_id_form')) {
        document.getElementById('device_id_form').value = deviceId;
        console.log('Device ID placé dans le formulaire:', deviceId);
    }
    
    // S'assurer que le device_id est bien transmis lors de la soumission du formulaire
    const pointageForm = document.getElementById('pointageForm');
    if (pointageForm) {
        // Vérifier les heures de travail autorisées
        function verifierHeuresTravail() {
            const maintenant = new Date();
            const heure = maintenant.getHours();
            const btnPointer = document.getElementById('btnPointer');
            const msgPointage = document.getElementById('msgPointage');
            const heuresAlert = document.getElementById('heuresAlert');
            
            // Autoriser le pointage entre 7h00 et 18h00
            if (heure < 7 || heure >= 18) {
                // En dehors des heures autorisées
                if (btnPointer && !btnPointer.hasAttribute('data-disabled-by-hours')) {
                    btnPointer.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500', 'hover:bg-gray-500');
                    btnPointer.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    btnPointer.disabled = true;
                    btnPointer.setAttribute('data-disabled-by-hours', 'true');
                    
                    if (msgPointage) {
                        msgPointage.classList.remove('text-green-500');
                        msgPointage.classList.add('text-red-500');
                        msgPointage.textContent = 'Le pointage n\'est pas disponible actuellement';
                    }
                    
                    if (heuresAlert) {
                        heuresAlert.classList.remove('hidden');
                    }
                }
                return false;
            } else {
                // Dans les heures autorisées
                if (btnPointer && btnPointer.hasAttribute('data-disabled-by-hours')) {
                    btnPointer.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-500', 'hover:bg-gray-500');
                    btnPointer.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    btnPointer.disabled = false;
                    btnPointer.removeAttribute('data-disabled-by-hours');
                    
                    if (msgPointage) {
                        msgPointage.classList.add('text-green-500');
                        msgPointage.classList.remove('text-red-500');
                        msgPointage.textContent = 'Vous pouvez pointer votre présence';
                    }
                    
                    if (heuresAlert) {
                        heuresAlert.classList.add('hidden');
                    }
                }
                return true;
            }
        }
        
        // Vérifier les heures au chargement de la page
        verifierHeuresTravail();
        
        // Vérifier les heures toutes les minutes
        setInterval(verifierHeuresTravail, 60000);
        
        pointageForm.addEventListener('submit', function(e) {
            // Vérifier si le pointage est autorisé à cette heure
            if (!verifierHeuresTravail()) {
                e.preventDefault();
                alert('Le pointage n\'est autorisé qu\'entre 7h00 et 18h00.');
                return false;
            }
            
            // Vérifier si le device_id est présent
            const deviceIdField = document.getElementById('device_id_form');
            if (!deviceIdField.value) {
                e.preventDefault(); // Empêcher la soumission
                deviceIdField.value = deviceId; // Assigner le device_id
                console.log('Device ID ajouté juste avant soumission:', deviceId);
                setTimeout(() => this.submit(), 100); // Soumettre après un court délai
            }
        });
    }
    
    // La partie qui soumettait automatiquement le formulaire a été supprimée pour éviter les rafraîchissements automatiques
    
    // Ajouter l'identifiant à tous les autres formulaires de la page
    document.querySelectorAll('form').forEach(form => {
        if (!form.querySelector('[name="device_id"]')) {
            const deviceIdInput = document.createElement('input');
            deviceIdInput.type = 'hidden';
            deviceIdInput.name = 'device_id';
            deviceIdInput.value = deviceId;
            form.appendChild(deviceIdInput);
        }
    });
});



function copyDeviceId() {
    const deviceId = localStorage.getItem('deviceId');
    navigator.clipboard.writeText(deviceId)
        .then(() => {
            const copyBtn = document.getElementById('copyDeviceIdBtn');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copié!';
            copyBtn.classList.remove('bg-blue-100', 'hover:bg-blue-200', 'text-blue-800');
            copyBtn.classList.add('bg-green-100', 'hover:bg-green-200', 'text-green-800');
            
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.classList.remove('bg-green-100', 'hover:bg-green-200', 'text-green-800');
                copyBtn.classList.add('bg-blue-100', 'hover:bg-blue-200', 'text-blue-800');
            }, 2000);
        })
        .catch(err => {
            console.error('Erreur lors de la copie: ', err);
            alert('Impossible de copier l\'ID de l\'appareil. Veuillez le sélectionner et copier manuellement.');
        });
}
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mettre à jour l'horloge toutes les secondes
        setInterval(updateClock, 1000);
        
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('horloge').textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        // Géolocalisation
        const getLocationBtn = document.getElementById('getLocationBtn');
        const localisationInput = document.getElementById('localisation');
        
        if (getLocationBtn && localisationInput) {
            getLocationBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Désactiver le bouton pendant la géolocalisation
                getLocationBtn.disabled = true;
                const originalContent = getLocationBtn.innerHTML;
                getLocationBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                try {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            // Succès
                            function(position) {
                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;
                                
                                // Formater les coordonnées pour le champ de saisie
                                localisationInput.value = `${latitude}, ${longitude}`;
                                
                                // Réinitialiser le bouton
                                getLocationBtn.disabled = false;
                                getLocationBtn.innerHTML = originalContent;
                                
                                // Notification de succès
                                alert('Localisation récupérée avec succès!');
                            },
                            // Erreur
                            function(error) {
                                console.error('Erreur de géolocalisation:', error);
                                let errorMessage = 'Géolocalisation non disponible';
                                
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMessage = 'Vous avez refusé l\'accès à votre position.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMessage = 'Votre position n\'est pas disponible.';
                                        break;
                                    case error.TIMEOUT:
                                        errorMessage = 'La demande de géolocalisation a expiré.';
                                        break;
                                }
                                
                                localisationInput.value = errorMessage;
                                alert(errorMessage);
                                
                                // Réinitialiser le bouton
                                getLocationBtn.disabled = false;
                                getLocationBtn.innerHTML = originalContent;
                            },
                            // Options
                            { 
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0 
                            }
                        );
                    } else {
                        localisationInput.value = 'Géolocalisation non supportée par votre navigateur';
                        alert('Géolocalisation non supportée par votre navigateur');
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML = originalContent;
                    }
                } catch (e) {
                    console.error('Erreur lors de la géolocalisation:', e);
                    localisationInput.value = 'Erreur lors de la géolocalisation';
                    alert('Erreur lors de la géolocalisation: ' + e.message);
                    getLocationBtn.disabled = false;
                    getLocationBtn.innerHTML = originalContent;
                }
            });
        }
    });
</script>
@endpush
</x-app-layout>
