<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    //
   
    use HasFactory;
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    // Conversion de expires_at en date
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Vérification de l'expiration de l'OTP
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
