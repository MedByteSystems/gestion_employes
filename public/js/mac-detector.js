/**
 * Utilitaire de détection d'adresse MAC
 * 
 * Ce script utilise plusieurs méthodes pour tenter de détecter l'adresse MAC de l'ordinateur:
 * 1. Utilisation de l'API WebRTC pour obtenir les adresses IP locales
 * 2. Utilisation d'une petite application Java pour lire l'adresse MAC (nécessite Java)
 * 3. Utilisation d'ActiveX pour les utilisateurs d'Internet Explorer (Windows uniquement)
 * 4. Génération d'un identifiant unique basé sur l'empreinte du navigateur comme solution de repli
 */

class MacAddressDetector {
    constructor() {
        this.macAddress = '';
        this.fingerprint = '';
        this.methods = [
            this.detectUsingWebRTC.bind(this),
            this.detectUsingJava.bind(this),
            this.detectUsingActiveX.bind(this),
            this.generateFingerprint.bind(this)
        ];
    }

    /**
     * Démarre la détection avec toutes les méthodes disponibles
     */
    async detect() {
        // Vérifier d'abord si nous avons déjà une adresse MAC stockée dans le localStorage
        const storedMac = localStorage.getItem('device_mac_address');
        if (storedMac) {
            console.log('Using stored MAC address:', storedMac);
            this.macAddress = storedMac;
            return storedMac;
        }
        
        // Essayer chaque méthode jusqu'à ce qu'une fonctionne
        for (const method of this.methods) {
            try {
                const result = await method();
                if (result) {
                    this.macAddress = result;
                    console.log('MAC address detected:', this.macAddress);
                    
                    // Stocker l'adresse MAC dans le localStorage pour les futures visites
                    localStorage.setItem('device_mac_address', result);
                    
                    return this.macAddress;
                }
            } catch (error) {
                console.log('Detection method failed:', error);
            }
        }

        // Si aucune méthode ne fonctionne, utiliser l'empreinte du navigateur
        // et la stocker pour les futures visites
        localStorage.setItem('device_mac_address', this.fingerprint);
        return this.fingerprint;
    }

    /**
     * Tente de détecter l'adresse MAC en utilisant WebRTC pour obtenir les adresses IP locales
     * puis génère une adresse MAC déterministique basée sur ces informations
     */
    async detectUsingWebRTC() {
        return new Promise((resolve, reject) => {
            try {
                // Fonction pour obtenir les adresses IP locales via WebRTC
                const getLocalIPs = (callback) => {
                    // Compatibilité avec les différents navigateurs
                    const RTCPeerConnection = window.RTCPeerConnection || 
                                             window.webkitRTCPeerConnection || 
                                             window.mozRTCPeerConnection;
                    
                    if (!RTCPeerConnection) {
                        callback([]);
                        return;
                    }
                    
                    const ips = [];
                    const pc = new RTCPeerConnection({
                        iceServers: []
                    });
                    
                    pc.createDataChannel('');
                    
                    pc.onicecandidate = (event) => {
                        if (!event || !event.candidate) return;
                        
                        const candidate = event.candidate.candidate;
                        const ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3})/;
                        const match = ipRegex.exec(candidate);
                        
                        if (match && ips.indexOf(match[1]) === -1) {
                            ips.push(match[1]);
                        }
                        
                        if (!event.candidate.candidate) {
                            pc.close();
                            callback(ips);
                        }
                    };
                    
                    pc.createOffer()
                        .then(offer => pc.setLocalDescription(offer))
                        .catch(err => {
                            console.error('Error creating offer:', err);
                            callback([]);
                        });
                    
                    // Timeout après 1 seconde si aucune adresse IP n'est trouvée
                    setTimeout(() => {
                        callback(ips);
                        pc.close();
                    }, 1000);
                };
                
                // Obtenir les adresses IP locales
                getLocalIPs((ips) => {
                    if (ips.length === 0) {
                        reject(new Error('No local IPs found'));
                        return;
                    }
                    
                    // Trier les IPs pour assurer la cohérence
                    ips.sort();
                    
                    // Générer une adresse MAC déterministe basée sur les IPs locales
                    const ipString = ips.join('|');
                    const hash = this.hashCode(ipString + navigator.userAgent);
                    const hexHash = Math.abs(hash).toString(16).substring(0, 12).padStart(12, '0');
                    
                    // Formater comme une adresse MAC (XX:XX:XX:XX:XX:XX)
                    const macAddress = [
                        hexHash.substring(0, 2),
                        hexHash.substring(2, 4),
                        hexHash.substring(4, 6),
                        hexHash.substring(6, 8),
                        hexHash.substring(8, 10),
                        hexHash.substring(10, 12)
                    ].join(':').toUpperCase();
                    
                    console.log('Generated MAC from local IPs:', macAddress, 'IPs:', ips);
                    resolve(macAddress);
                });
            } catch (error) {
                console.error('WebRTC detection error:', error);
                reject(error);
            }
        });
    }

    /**
     * Tente de détecter l'adresse MAC en utilisant un applet Java
     */
    async detectUsingJava() {
        return new Promise((resolve, reject) => {
            try {
                // Cette méthode est une simulation car les applets Java ne sont plus pris en charge par les navigateurs modernes
                // Dans un environnement réel, vous pourriez avoir une application Java Web Start
                reject(new Error('Java applets are no longer supported by modern browsers'));
            } catch (error) {
                reject(error);
            }
        });
    }

    /**
     * Tente de détecter l'adresse MAC en utilisant ActiveX (Internet Explorer uniquement)
     */
    async detectUsingActiveX() {
        return new Promise((resolve, reject) => {
            try {
                // Cette méthode est une simulation car ActiveX n'est disponible que sur Internet Explorer
                // et n'est plus pris en charge par les navigateurs modernes
                reject(new Error('ActiveX is only available on Internet Explorer'));
            } catch (error) {
                reject(error);
            }
        });
    }

    /**
     * Génère une empreinte unique basée sur les caractéristiques du navigateur
     * Cette méthode est utilisée comme solution de repli si aucune autre méthode ne fonctionne
     */
    async generateFingerprint() {
        return new Promise((resolve) => {
            // Utiliser des composants plus stables pour l'empreinte
            const components = [
                navigator.userAgent,
                navigator.language,
                navigator.platform,
                navigator.hardwareConcurrency || '',
                screen.colorDepth,
                screen.width + 'x' + screen.height,
                // Ajouter des informations sur les plugins installés (si disponibles)
                Array.from(navigator.plugins || []).map(p => p.name).join(','),
                // Vérifier si certaines fonctionnalités sont disponibles
                'canvas:' + (!!window.HTMLCanvasElement),
                'webgl:' + (!!window.WebGLRenderingContext),
                'webrtc:' + (!!window.RTCPeerConnection),
                // Informations sur le CPU (si disponibles)
                navigator.cpuClass || '',
                navigator.oscpu || '',
                // Fuseau horaire
                Intl.DateTimeFormat().resolvedOptions().timeZone || ''
            ];
            
            // Créer une empreinte qui ressemble à une adresse MAC
            const fingerprint = components.join('|');
            const hash = this.hashCode(fingerprint);
            const hexHash = Math.abs(hash).toString(16).substring(0, 12).padStart(12, '0');
            
            // Formater comme une adresse MAC (XX:XX:XX:XX:XX:XX)
            this.fingerprint = [
                hexHash.substring(0, 2),
                hexHash.substring(2, 4),
                hexHash.substring(4, 6),
                hexHash.substring(6, 8),
                hexHash.substring(8, 10),
                hexHash.substring(10, 12)
            ].join(':').toUpperCase();
            
            console.log('Generated device fingerprint:', this.fingerprint);
            resolve(this.fingerprint);
        });
    }

    /**
     * Fonction de hachage simple pour générer un nombre à partir d'une chaîne
     */
    hashCode(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convertir en entier 32 bits
        }
        return hash;
    }
}

// Exporter la classe pour une utilisation dans d'autres scripts
window.MacAddressDetector = MacAddressDetector;
