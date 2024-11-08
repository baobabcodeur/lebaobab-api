<?php

namespace App\Services;

use Paydunya\Setup;
use Paydunya\Checkout\CheckoutSubscription;

class PayDunyaService
{
    public function __construct()
    {
        Setup::setMasterKey(config('services.paydunya.master_key'));
        Setup::setPrivateKey(config('services.paydunya.private_key'));
        Setup::setToken(config('services.paydunya.token'));
        Setup::setMode(config('services.paydunya.mode')); // "test" ou "live"
        Setup::setAccountName("lebaobab"); // Nom affiché sur la page de paiement
    }

    public function createSubscription($plan_name, $amount, $interval, $description, $callback_url)
    {
        $subscription = new CheckoutSubscription();
        $subscription->setName($plan_name);
        $subscription->setDescription($description);
        $subscription->setAmount($amount);
        $subscription->setInterval($interval); // Par exemple: "monthly", "yearly", etc.
        $subscription->setCallbackUrl($callback_url);

        if ($subscription->create()) {
            return [
                'success' => true,
                'subscription_url' => $subscription->getInvoiceUrl()
            ];
        } else {
            return [
                'success' => false,
                'error' => $subscription->response_text
            ];
        }
    }

    public function cancelSubscription($token)
    {
        $subscription = new CheckoutSubscription();
        return $subscription->cancel($token);
    }
}
