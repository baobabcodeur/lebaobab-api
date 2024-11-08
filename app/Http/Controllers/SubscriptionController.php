<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Paydunya\Setup;
use Paydunya\Checkout\CheckoutInvoice;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Paydunya\Checkout\Store;

class SubscriptionController extends Controller
{
    public function __construct()
    {

        Setup::setMasterKey(config('paydunya.master_key'));
        Setup::setPrivateKey(config('paydunya.private_key'));
        Setup::setToken(config('paydunya.token'));
        Setup::setPublicKey(config('paydunya.public_key'));
        Setup::setMode(config('paydunya.mode'));
        Store::setName(config('paydunya.name'));
        // Configuration de PayDunya
       
    }

    /**
     * Crée un abonnement et initie une transaction PayDunya.
     */
    public function createSubscription(Request $request)
    {
        // Valider les données de l'abonnement
        $request->validate([
            'type' => 'required|string', // ex: "Mensuel", "Annuel", etc.
        ]);
    
        // Instancier une facture de paiement PayDunya
        $invoice = new CheckoutInvoice();
    
        // Définir les informations de l'entreprise et les URLs de retour et d'annulation sur l'objet CheckoutInvoice
        $invoice->addCustomData('company_name', 'Votre Nom d\'Entreprise');
        $invoice->setCancelUrl(url('/subscription/cancel'));
        $invoice->setReturnUrl(url('/subscription/callback'));
    
        // Ajouter les détails de la transaction
        $invoice->addItem("Abonnement " . $request->type, 1, 1000, 1000, "Abonnement de type " . $request->type);
    
        // Ajouter les informations de l'abonné
        $invoice->setTotalAmount(1000); // Remplacez par le montant réel de l'abonnement
        $invoice->setDescription("Abonnement " . $request->type);
    
        // Enregistrer l'abonnement localement
        $subscription = new Subscription();
        $subscription->user_id = Auth::id();
        $subscription->type = $request->type;
        $subscription->status = 'pending';
        $subscription->save();
    
        // Lancer la transaction
        if ($invoice->create()) {
            // Associer l'URL de paiement à l'abonnement
            $subscription->payment_url = $invoice->getInvoiceUrl();
            $subscription->save();
    
            // Retourner la réponse avec l'URL de paiement
            return response()->json([
                'message' => 'Abonnement créé avec succès.',
                'payment_url' => $invoice->getInvoiceUrl()
            ], 200);
        } else {
            // Erreur lors de la création de la transaction
            return response()->json([
                'error' => 'Erreur lors de la création de la facture PayDunya.',
                'details' => $invoice->response_text
            ], 500);
        }
    }
    
    /**
     * Gère le retour de PayDunya après le paiement.
     */
    public function callback(Request $request)
    {
        $invoice = new CheckoutInvoice();

        if ($invoice->confirm($request->token)) {
            // Récupérer l'abonnement basé sur le token de transaction
            $subscription = Subscription::where('payment_url', $invoice->getInvoiceUrl())->first();

            if ($invoice->getStatus() == "completed" && $subscription) {
                // Mise à jour de l'abonnement en tant que validé
                $subscription->status = 'active';
                $subscription->start_date = now();
                $subscription->end_date = now()->addMonth(); // Durée d'un mois, ajustez selon votre logique
                $subscription->save();

                return response()->json(['message' => 'Paiement réussi, abonnement activé.'], 200);
            } else {
                // Mise à jour de l'abonnement en tant qu'échoué
                if ($subscription) {
                    $subscription->status = 'failed';
                    $subscription->save();
                }
                return response()->json(['message' => 'Le paiement a échoué.'], 400);
            }
        } else {
            return response()->json(['error' => 'Erreur de confirmation de paiement.'], 500);
        }
    }
}
