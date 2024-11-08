<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('plan_name')->after('user_id'); // Nom du plan d'abonnement
            $table->decimal('amount', 10, 2)->after('plan_name'); // Montant de l'abonnement
            $table->string('interval')->after('amount'); // Intervalle de l'abonnement (ex: 'monthly', 'yearly')
            $table->string('status')->default('pending')->after('interval'); // Statut de l'abonnement
            $table->string('subscription_token')->nullable()->after('status'); // Token unique d'abonnement
            $table->dateTime('expires_at')->nullable()->after('subscription_token'); // Date d'expiration de l'abonnement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'plan_name', 
                'amount', 
                'interval', 
                'status', 
                'subscription_token', 
                'expires_at'
            ]);
        });
    }
};
