<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // Méthode pour obtenir la liste des utilisateurs
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // Méthode pour afficher un utilisateur spécifique
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }
}
