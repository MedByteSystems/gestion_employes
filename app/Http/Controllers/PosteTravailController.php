<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\PosteTravail;
use App\Utilities\MacAddressGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PosteTravailController extends Controller
{
    /**
     * Affiche la liste des postes de travail
     */
    public function index()
    {
        $postesTravail = PosteTravail::with('employe')->paginate(10);
        $employes = Employe::all();
        
        return view('admin.postes_travail.index', compact('postesTravail', 'employes'));
    }
    
    /**
     * Affiche le formulaire de création d'un poste de travail
     */
    public function create(Request $request)
    {
        $employes = Employe::all();
        
        // Récupérer l'adresse IP
        $ipAddress = $request->ip();
        
        return view('admin.postes_travail.create', compact('employes', 'ipAddress'));
    }
    

    
    /**
     * Enregistre un nouveau poste de travail
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employe_id' => 'required|exists:employes,id',
            'nom' => 'required|string|max:255',
            'device_id' => 'required|string|max:64',
            'localisation' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Créer le poste de travail avec les données du formulaire
        $posteTravail = PosteTravail::create($request->all());
        
        return redirect()->route('admin.postes-travail.index')
            ->with('success', 'Poste de travail créé avec succès. Ce poste est associé à l\'appareil avec l\'ID : ' . $request->device_id);
    }
    
    /**
     * Affiche le formulaire d'édition d'un poste de travail
     */
    public function edit(PosteTravail $postes_travail)
    {
        $employes = Employe::all();
        $posteTravail = $postes_travail; // Pour maintenir la compatibilité avec la vue
        return view('admin.postes_travail.edit', compact('posteTravail', 'employes'));
    }
    
    /**
     * Met à jour un poste de travail
     */
    public function update(Request $request, PosteTravail $postes_travail)
    {
        $posteTravail = $postes_travail; // Pour maintenir la compatibilité avec le code existant
        
        $validator = Validator::make($request->all(), [
            'employe_id' => 'required|exists:employes,id',
            'nom' => 'required|string|max:255',
            'device_id' => 'required|string|max:64',
            'localisation' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Préparer les données à mettre à jour
        $data = $request->all();
        
        // Gérer le champ actif (checkbox)
        $data['actif'] = $request->has('actif') ? 1 : 0;
        
        $posteTravail->update($data);
        
        return redirect()->route('admin.postes-travail.index')
            ->with('success', 'Poste de travail mis à jour avec succès');
    }
    
    /**
     * Supprime un poste de travail
     */
    public function destroy(PosteTravail $postes_travail)
    {
        $postes_travail->delete();
        
        return redirect()->route('admin.postes-travail.index')
            ->with('success', 'Poste de travail supprimé avec succès');
    }
    
    /**
     * Affiche la page de gestion des postes de travail pour un employé
     */
    public function employePostes(Employe $employe)
    {
        $postesTravail = $employe->postesTravail()->get();
        
        return view('admin.postes_travail.employe', compact('employe', 'postesTravail'));
    }
    
    /**
     * Recherche des employés par nom ou prénom
     * Utilisé pour l'autocomplétion dans les formulaires
     */
    public function searchEmployes(Request $request)
    {
        // Log pour le débogage
        \Log::info('Recherche d\'employés - Requête reçue', [
            'query' => $request->input('query'),
            'method' => $request->method(),
            'all_params' => $request->all(),
            'url' => $request->fullUrl(),
            'headers' => $request->header()
        ]);
        
        // Débogage - Afficher un message dans la console
        error_log('Recherche d\'employés - Requête reçue: ' . $request->input('query'));
        
        // Créer des employés de test si la table est vide
        if (Employe::count() === 0) {
            // Créer quelques employés de démonstration
            Employe::create(['last_name' => 'Dupont', 'first_name' => 'Jean', 'position' => 'Développeur']);
            Employe::create(['last_name' => 'Martin', 'first_name' => 'Sophie', 'position' => 'Designer']);
            Employe::create(['last_name' => 'Dubois', 'first_name' => 'Pierre', 'position' => 'Manager']);
            Employe::create(['last_name' => 'Leroy', 'first_name' => 'Marie', 'position' => 'Comptable']);
            Employe::create(['last_name' => 'Moreau', 'first_name' => 'Thomas', 'position' => 'Technicien']);
            Employe::create(['last_name' => 'Taoufiki', 'first_name' => 'Amina', 'position' => 'Directrice']);
            
            // Log pour indiquer que des employés ont été créés
            \Log::info('Des employés de test ont été créés');
        }
        
        $query = $request->input('query', '');
        
        // Si la recherche est par ID (format id:123)
        if (strpos($query, 'id:') === 0) {
            $id = substr($query, 3);
            $employe = Employe::find($id);
            
            if ($employe) {
                // Formater la réponse pour correspondre à l'interface attendue
                return response()->json([[
                    'id' => $employe->id,
                    'nom' => $employe->last_name,
                    'prenom' => $employe->first_name
                ]]);
            }
            
            return response()->json([]);
        }
        
        // Recherche par nom/prénom ou retourner tous les employés si la requête est vide
        $query = Employe::query();
        
        if (!empty($request->input('query'))) {
            $searchTerm = $request->input('query');
            $query->where(function($q) use ($searchTerm) {
                $q->where('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('first_name', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Récupérer les employés et formater la réponse
        $employes = $query->take(10)->get();
        
        // Transformer les données pour correspondre à l'interface attendue
        $formattedEmployes = $employes->map(function($employe) {
            return [
                'id' => $employe->id,
                'nom' => $employe->last_name,
                'prenom' => $employe->first_name
            ];
        });
        
        // Débogage - Afficher les données brutes des employés
        \Log::info('Données brutes des employés', [
            'employes_raw' => $employes->toArray()
        ]);
        
        // Log des résultats pour débogage
        \Log::info('Résultats de la recherche', [
            'nombre_resultats' => count($formattedEmployes),
            'resultats' => $formattedEmployes->toArray()
        ]);
        
        // Ajouter un délai artificiel pour débogage
        // sleep(1);
        
        return response()->json($formattedEmployes);
    }
    
    /**
     * Génère une adresse MAC unique basée sur les caractéristiques de l'ordinateur
     * Cette méthode est appelée via AJAX depuis la page de création de poste de travail
     */
    public function genererMac(Request $request)
    {
        // Vérifier que la requête contient les données nécessaires
        $validator = Validator::make($request->all(), [
            'userAgent' => 'required|string',
            'screenResolution' => 'required|string',
            'timezone' => 'required|string',
            'language' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => 'Données insuffisantes pour générer une adresse MAC'], 400);
        }
        
        // Récupérer les données de la requête
        $userAgent = $request->input('userAgent');
        $screenResolution = $request->input('screenResolution');
        $timezone = $request->input('timezone');
        $language = $request->input('language');
        $ipAddress = $request->ip();
        
        // Créer une chaîne unique basée sur ces données
        $uniqueString = $userAgent . '|' . $screenResolution . '|' . $timezone . '|' . $language . '|' . $ipAddress;
        
        // Générer un hash MD5 de cette chaîne
        $hash = md5($uniqueString);
        
        // Convertir le hash en format d'adresse MAC (XX:XX:XX:XX:XX:XX)
        $macAddress = substr($hash, 0, 2) . ':' . 
                     substr($hash, 2, 2) . ':' . 
                     substr($hash, 4, 2) . ':' . 
                     substr($hash, 6, 2) . ':' . 
                     substr($hash, 8, 2) . ':' . 
                     substr($hash, 10, 2);
        
        // Convertir en majuscules pour une meilleure lisibilité
        $macAddress = strtoupper($macAddress);
        
        // Vérifier si cette adresse MAC existe déjà dans la base de données
        $existingPoste = PosteTravail::where('adresse_mac', $macAddress)->first();
        
        // Retourner l'adresse MAC générée
        return response()->json([
            'mac_address' => $macAddress,
            'already_exists' => $existingPoste ? true : false,
            'device_info' => [
                'user_agent' => $userAgent,
                'screen_resolution' => $screenResolution,
                'timezone' => $timezone,
                'language' => $language,
                'ip_address' => $ipAddress
            ]
        ]);
    }
}
