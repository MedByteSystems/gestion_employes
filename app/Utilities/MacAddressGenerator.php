<?php

namespace App\Utilities;

use Illuminate\Http\Request;

class MacAddressGenerator
{
    /**
     * Génère une adresse MAC unique pour l'appareil de l'utilisateur
     * 
     * @param Request $request
     * @return string
     */
    public static function generate(Request $request)
    {
        // Collecter des informations sur l'appareil
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');
        $acceptEncoding = $request->header('Accept-Encoding');
        
        // Créer une empreinte unique en combinant ces informations
        $deviceFingerprint = $userAgent . '|' . $ipAddress . '|' . $acceptLanguage . '|' . $acceptEncoding;
        
        // Générer un hash à partir de l'empreinte
        $hash = md5($deviceFingerprint);
        
        // Formater le hash comme une adresse MAC (XX:XX:XX:XX:XX:XX)
        $macAddress = implode(':', str_split(substr($hash, 0, 12), 2));
        $macAddress = strtoupper($macAddress);
        
        return $macAddress;
    }
}
