<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de Vérification - {{ config('app.name') }}</title>
    <meta http-equiv="refresh" content="300"> <!-- Rafraîchir la page toutes les 5 minutes -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-4xl w-full mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-blue-600 p-6 text-white">
                <h1 class="text-3xl font-bold text-center">Code de Vérification du Jour</h1>
                <p class="text-center text-blue-100 mt-2">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            
            <div class="p-8 text-center">
                <div class="mb-8">
                    <p class="text-gray-600 text-lg mb-4">Utilisez ce code pour pointer votre présence aujourd'hui</p>
                    <div class="bg-gray-100 rounded-xl p-8 mb-6">
                        <div class="text-7xl font-mono font-bold tracking-widest text-blue-700 mb-2">
                            {{ $codeVerification }}
                        </div>
                        <p class="text-gray-500">Ce code change automatiquement chaque jour à minuit</p>
                    </div>
                    
                    <div class="text-xl font-semibold">
                        Heure actuelle : <span class="text-blue-600" id="horloge">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-semibold mb-4">Instructions</h2>
                    <div class="text-left text-gray-600 space-y-2">
                        <p>1. Connectez-vous à votre compte sur l'intranet de l'entreprise</p>
                        <p>2. Accédez à la page de pointage</p>
                        <p>3. Saisissez ce code dans le champ "Code de vérification"</p>
                        <p>4. Complétez le formulaire et validez votre présence</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 text-center text-gray-500 text-sm">
                <p>Ce code n'est valable que depuis le réseau de l'entreprise</p>
                <p>En cas de problème, contactez le service informatique</p>
            </div>
        </div>
    </div>

    <script>
        // Mise à jour de l'horloge en temps réel
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('horloge').textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        setInterval(updateClock, 1000);
    </script>
</body>
</html>
