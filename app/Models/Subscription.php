<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    // Indiquez les colonnes de la table qui peuvent être assignées en masse
    protected $fillable = [
        'user_id',
        'plan_name',
        'amount',
        'interval',
        'status',
        'subscription_token',
        'expires_at',
    ];

    // Types de données pour les colonnes
    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    // Constantes pour les différents types d'intervalles
    const INTERVAL_MONTHLY = 'monthly';
    const INTERVAL_YEARLY = 'yearly';

    // Constantes pour les statuts possibles
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_EXPIRED = 'expired';

    /**
     * Relation avec le modèle User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si l'abonnement est actif.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->expires_at > Carbon::now();
    }

    /**
     * Génère un token unique pour l'abonnement.
     */
    public static function generateSubscriptionToken(): string
    {
        return bin2hex(random_bytes(16)); // Génère un token unique de 32 caractères
    }
}
