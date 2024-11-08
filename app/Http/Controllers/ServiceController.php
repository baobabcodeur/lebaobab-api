<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{

    public function count()
{
    $count = Service::count();
    return response()->json(['count' => $count]);
}


    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'imgFile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour les images
            'price' => 'required|numeric|min:0',
            'freelancerId' => 'nullable|exists:users,id', // Vérifie si l'ID existe dans la table `users`
            'type' => 'required|in:en ligne,presentiel',
        ]);

        // Si un fichier image est envoyé, on le sauvegarde
        $data = $request->all();
        if ($request->hasFile('imgFile')) {
            $path = $request->file('imgFile')->store('services', 'public');
            $data['imgFile'] = $path; // Stocke le chemin de l'image
        }

        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function index(): JsonResponse
    {
        $services = Service::all();
        return response()->json($services, 200);
    }

    public function show($id): JsonResponse
    {
        $service = Service::with('user')->findOrFail($id); // Inclure les informations du freelancer
        return response()->json($service, 200);
    }

    public function delete($id): JsonResponse
    {
        $service = Service::findOrFail($id);

        // Supprimer l'image associée s'il y en a une
        if ($service->imgFile) {
            Storage::disk('public')->delete($service->imgFile);
        }

        $service->delete();

        return response()->json(['message' => 'Service supprimé avec succès'], 200);
    }
}
