/**
 * Script pour générer une adresse MAC unique basée sur les caractéristiques de l'appareil
 */
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du DOM
    var genererBtn = document.getElementById('genererMacBtn');
    var macInput = document.getElementById('adresse_mac');
    
    if (genererBtn && macInput) {
        console.log('Bouton de génération MAC trouvé et initialisé');
        
        // Ajouter un gestionnaire d'événement au clic sur le bouton
        genererBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut du bouton
            
            // Collecter des informations sur l'appareil
            var deviceInfo = {
                userAgent: navigator.userAgent,
                screen: window.screen.width + 'x' + window.screen.height,
                colorDepth: window.screen.colorDepth,
                platform: navigator.platform,
                language: navigator.language,
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                time: new Date().getTime()
            };
            
            // Créer une chaîne unique à partir des informations
            var infoString = JSON.stringify(deviceInfo);
            
            // Générer un hash simple
            var hash = 0;
            for (var i = 0; i < infoString.length; i++) {
                var char = infoString.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash; // Convertir en entier 32 bits
            }
            
            // Convertir en hexadécimal positif
            var hexString = Math.abs(hash).toString(16).padStart(12, '0');
            
            // Formater comme une adresse MAC (XX:XX:XX:XX:XX:XX)
            var macAddress = [
                hexString.substring(0, 2),
                hexString.substring(2, 4),
                hexString.substring(4, 6),
                hexString.substring(6, 8),
                hexString.substring(8, 10),
                hexString.substring(10, 12)
            ].join(':').toUpperCase();
            
            console.log('Adresse MAC générée:', macAddress);
            
            // Mettre à jour le champ d'adresse MAC
            macInput.value = macAddress;
            
            // Afficher une alerte pour confirmer
            alert('Adresse MAC générée : ' + macAddress);
        });
    } else {
        console.error('Bouton de génération MAC ou champ adresse MAC non trouvé');
    }
});
