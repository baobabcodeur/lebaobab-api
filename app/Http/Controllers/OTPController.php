<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendOTP;

class OTPController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Validation des données de la requête
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'message' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
        ]);

        // Générer un OTP
      

        // Envoyer l'OTP à votre adresse e-mail (ou à l'utilisateur si vous préférez)
        Mail::to('your-email@example.com')->send(new SendOTP($validated['full_name'], $validated['message'], $validated['email'], $validated['phone_number']));

        // Retourner une réponse
        return response()->json([
            'message' => 'OTP envoyé avec succès.',
           // Notez que vous pouvez aussi ne pas renvoyer l'OTP pour des raisons de sécurité
        ], 200);
    }
}
