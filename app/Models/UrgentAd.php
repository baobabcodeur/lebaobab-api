<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrgentAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'valid_until', 'user_id', 'number', 'price', 'city', 'type', 'file_path'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
