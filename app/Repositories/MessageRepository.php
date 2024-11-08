<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest; // Assurez-vous que vous avez créé AuthRequest pour la validation
use App\Models\User; // Importation du modèle User
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(AuthRequest $request): JsonResponse
    {
        // Crée un nouvel utilisateur
        $user = User::create($request->validated()); // Utilisation directe du modèle User
        return response()->json(['user' => $user], 201);
    }

    /**
     * Log in the user.
     */
    public function login(AuthRequest $request): JsonResponse
    {
        // Vérifiez les identifiants de l'utilisateur
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        // Générer un token si vous utilisez Sanctum ou Passport pour l'authentification
        $token = $user->createToken('lebaobab')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    /**
     * Log out the user.
     */
    public function logout(): JsonResponse
    {
        // Déconnexion de l'utilisateur
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
