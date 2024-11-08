<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{



    // Fonction pour créer un projet
    public function store(Request $request): JsonResponse
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:en attente,en cours,terminé,annulé',
            'user_id' => 'required|exists:users,id',  // Assure que l'user_id existe dans la table users
            'imgFile' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',  // Validation de l'image
            'budget' => 'nullable|numeric|min:0',  // Validation du budget
            'type' => 'required|in:en ligne,presentiel',  // Validation du type
        ]);

        // Traitement de l'image si elle est présente
        if ($request->hasFile('imgFile')) {
            // Enregistrer le fichier et obtenir son chemin
            $filePath = $request->file('imgFile')->store('project_images', 'public');
        } else {
            $filePath = null; // Si aucun fichier n'est fourni
        }

        // Création du projet
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'imgFile' => $filePath,  // Chemin de l'image
            'budget' => $request->budget,
            'type' => $request->type,
        ]);

        return response()->json($project, 201);  // Retourner le projet créé
    }

    // Fonction pour récupérer la liste des projets
    public function index(): JsonResponse
    {
        
        $projects = Project::all();  // Récupérer tous les projets
        return response()->json($projects, 200);
    }

    // Fonction pour afficher un projet spécifique
    public function show($id): JsonResponse
    {
        $project = Project::findOrFail($id);  // Récupérer le projet par ID
        return response()->json($project, 200);
    }

    // Fonction pour mettre à jour un projet
    public function update(Request $request, $id): JsonResponse
    {
        $project = Project::findOrFail($id);  // Récupérer le projet à mettre à jour

        // Validation des données à mettre à jour
        $validated = $request->validate([
            'title' => 'nullable|string', // champ title optionnel
            'description' => 'nullable|string', // champ description optionnel
            'status' => 'nullable|in:en attente,en cours,terminé,annulé',
            'user_id' => 'nullable|exists:users,id',  // L'ID utilisateur doit exister
            'imgFile' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',  // Validation de l'image
            'budget' => 'nullable|numeric|min:0',  // Validation du budget
            'type' => 'nullable|in:en ligne,presentiel',  // Validation du type
        ]);

        // Traitement de l'image si elle est présente
        if ($request->hasFile('imgFile')) {
            // Supprimer l'image précédente du stockage
            if ($project->imgFile && Storage::exists('public/' . $project->imgFile)) {
                Storage::delete('public/' . $project->imgFile);
            }

            // Enregistrer le fichier et obtenir son chemin
            $filePath = $request->file('imgFile')->store('project_images', 'public');
        } else {
            // Si aucune nouvelle image n'est envoyée, conserver l'ancienne
            $filePath = $project->imgFile;
        }

        // Mise à jour du projet avec les données envoyées
        $project->update([
            'title' => $request->title ?? $project->title,  // Si title n'est pas envoyé, conserver l'ancien
            'description' => $request->description ?? $project->description,  // Si description n'est pas envoyé, conserver l'ancienne
            'status' => $request->status ?? $project->status,  // Si status n'est pas envoyé, conserver l'ancien
            'user_id' => $request->user_id ?? $project->user_id,  // Si user_id n'est pas envoyé, conserver l'ancien
            'imgFile' => $filePath,  // Met à jour le chemin de l'image si nouvelle image
            'budget' => $request->budget ?? $project->budget,  // Si budget n'est pas envoyé, conserver l'ancien
            'type' => $request->type ?? $project->type,  // Si type n'est pas envoyé, conserver l'ancien
        ]);

        return response()->json($project, 200);
    }

    // Fonction pour supprimer un projet
    public function destroy($id): JsonResponse
    {
        $project = Project::findOrFail($id);  // Récupérer le projet par ID

        // Supprimer le fichier image du stockage, s'il existe
        if ($project->imgFile && Storage::exists('public/' . $project->imgFile)) {
            Storage::delete('public/' . $project->imgFile);
        }

        // Supprimer le projet de la base de données
        $project->delete();

        return response()->json(null, 204);  // Retourner une réponse de succès sans contenu
    }


    public function countProject(): JsonResponse
    {

        $count = Project::count();

        return response()->json(['count' => $count]);
    }

   
}
