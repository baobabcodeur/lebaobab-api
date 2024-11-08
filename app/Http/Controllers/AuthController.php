<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OtpCodeMail;
use App\Mail\OtpMail;
use App\Mail\ResetPasswordMail;
use App\Models\Otp;
use Exception;

class AuthController extends Controller
{
  

    /**
     * Register a new user and send an OTP to their email.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Créer l'utilisateur sans l'enregistrer dans la base de données
        $user = User::make([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        // Générer un code OTP
        $otp = rand(100000, 999999); // Générer un OTP à 6 chiffres
        $expiresAt = now()->addMinutes(5); // L'OTP expire dans 5 minutes
    
        // Enregistrer l'OTP dans la base de données
        Otp::create([
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);
    
        // Envoyer l'OTP par email
        try {
            Mail::to($user->email)->send(new OtpMail($user->name, $user->email, $otp));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()], 500);
        }
    
        return response()->json(['message' => "L'utilisateur s'est enregistré avec succès. Un OTP a été envoyé à votre adresse e-mail."], 201);
    }
    

    /**
     * Verify the OTP sent to the user's email.
     */
    public function verifyOtp(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|string|email',
        'otp' => 'required|string',
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Récupérer l'OTP correspondant à l'email
    $otpRecord = Otp::where('email', $request->email)->first();

    // Vérifier la validité de l'OTP
    if (!$otpRecord || $otpRecord->otp !== $request->otp || $otpRecord->expires_at->isPast()) {
        return response()->json(['message' => 'OTP invalide ou expiré.'], 400);
    }

    // Enregistrer l'utilisateur dans la base de données
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Supprimer l'OTP après utilisation
    $otpRecord->delete();

    return response()->json(['message' => 'OTP vérifié avec succès. Utilisateur enregistré.'], 200);
}


    

    /**
     * Request a password reset link via email.
     */
    public function requestPasswordReset(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|string|email']);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'Email non trouvé.'], 404);
        }
    
        // Générer un code OTP
        $otp = mt_rand(100000, 999999); // Générer un OTP à 6 chiffres
    
        // Enregistrer l'OTP dans la base de données
        Otp::updateOrCreate(
            ['email' => $user->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)] // L'OTP expire après 10 minutes
        );
    
        // Envoyer l'OTP par e-mail
        Mail::to($user->email)->send(new OtpMail($user->name, $user->email, $otp));
    
        return response()->json(['message' => 'Un code OTP a été envoyé à votre adresse e-mail.'], 200);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|string', // Ajouter la validation de l'OTP
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Récupérer l'OTP correspondant à l'email
        $otpRecord = Otp::where('email', $request->email)->first();

        // Vérifier la validité de l'OTP
        if (!$otpRecord || $otpRecord->otp !== $request->otp || $otpRecord->expires_at->isPast()) {
            return response()->json(['message' => 'OTP invalide ou expiré.'], 400);
        }

        // Vérifiez que l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email non trouvé.'], 404);
        }

        // Mettre à jour le mot de passe
        $user->password = bcrypt($request->password);
        $user->save();

        // Supprimer l'OTP après utilisation
        $otpRecord->delete();

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.'], 200);
    }

    public function login(Request $request): JsonResponse
    {
        // Valider les données de la requête
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
    
        // Essayer d'authentifier l'utilisateur
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentification réussie, récupérer l'utilisateur
            $user = Auth::user();
    
            // Générer un token pour l'utilisateur
            $user->token = $user->createToken($user->id)->plainTextToken;
    
            // Retourner le token et les informations de l'utilisateur
            return response()->json([
                'message' => 'Connexion réussie.',
                'user' => $user,
                 // Renvoie le token sous forme de chaîne
            ], 200);
        }
    
        // Retourner une réponse d'erreur si l'authentification échoue
        return response()->json(['message' => 'Identifiants invalides.'], 401);
    }
    
    

    public function logout(Request $request): JsonResponse {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }
        // Révoquer le token actuel
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie.'], 200);
    }
    
    

}


