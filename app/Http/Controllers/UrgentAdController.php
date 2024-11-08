<?php

namespace App\Http\Controllers;

use App\Models\UrgentAd;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UrgentAdController extends Controller
{

    public function count()
{
    $count = UrgentAd::count();
    return response()->json(['count' => $count]);
}

    /**
 * Get all urgent ads.
 */
public function index(): JsonResponse
{
    $urgentAds = UrgentAd::all();  // Retrieves all urgent ads from the database
    return response()->json($urgentAds, 200);
}

    /**
     * Store a new urgent ad.
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données entrantes
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'nullable|string|max:255',
            'type' => 'required|in:présentiel,en ligne',
            'valid_until' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'number' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Stockage du fichier si présent avec le nom d'origine
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $uniqueFileName = Str::random(8) . '_' . $originalFileName; // Préfix unique pour éviter les doublons
            $filePath = $file->storeAs('storage/urgent_ads', $uniqueFileName, 'public'); // Sauvegarde dans storage/app/public/urgent_ads
        }

        // Création d'une nouvelle annonce urgente
        $urgentAd = UrgentAd::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'city' => $request->city,
            'type' => $request->type,
            'valid_until' => $request->valid_until,
            'user_id' => $request->user_id,
            'number' => $request->number,
            'file_path' => 'http://127.0.0.1:8000/storage/'.$filePath
        ]);

        return response()->json($urgentAd, 201);
    }

 
    
    public function update(Request $request, $id): JsonResponse
    {
       
        // Trouver l'annonce par ID ou lancer une erreur 404 si l'annonce n'existe pas
        $urgentAd = UrgentAd::findOrFail($id);
    
        // Validation des données entrantes
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'city' => 'nullable|string|max:255',
            'type' => 'nullable|in:présentiel,en ligne',
            'valid_until' => 'nullable|date|after:now',
            'number' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
    
        // Mise à jour du fichier si un nouveau est fourni
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($urgentAd->file_path) {
                Storage::disk('public')->delete($urgentAd->file_path);
            }
    
            // Récupérer le fichier et lui donner un nom unique
            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            $uniqueFileName = Str::random(8) . '_' . $originalFileName; // Préfixe unique pour éviter les doublons
            $filePath = $file->storeAs('urgent_ads/storage', $uniqueFileName, 'public');
    
            // Mise à jour du chemin du fichier dans le modèle
            $urgentAd->file_path = 'http://127.0.0.1:8000/storage/'.$filePath;
        }
        
    
        // Mise à jour des autres attributs de l'annonce
        $urgentAd->update($request->only(['title', 'description', 'price', 'city', 'type', 'valid_until', 'number']));
    
        // Retourner la réponse avec l'annonce mise à jour
        return response()->json($urgentAd->fresh(), 200); // Utilisation de fresh() pour récupérer les données actualisées
    }
    
    

    /**
     * Delete an urgent ad by ID.
     */
    public function destroy($id): JsonResponse
    {
        $urgentAd = UrgentAd::findOrFail($id);

        // Supprimer le fichier associé s'il existe
        if ($urgentAd->file_path) {
            Storage::disk('public')->delete($urgentAd->file_path);
        }

        // Supprimer l'annonce
        $urgentAd->delete();

        return response()->json(null, 204);
    }
}
