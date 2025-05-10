<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMacAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    

     public function handle($request, Closure $next)
     {
         $mac = $request->mac_address; // À récupérer via une méthode sécurisée
         
         if (!Appareil::where('mac_address', $mac)
             ->where('employe_id', auth()->user()->employe->id)
             ->exists()) {
             
             Log::warning('Tentative de pointage non autorisée', [
                 'user' => auth()->id(),
                 'mac' => $mac
             ]);
             
             abort(403, 'Appareil non autorisé');
         }
 
         return $next($request);
     }
}
