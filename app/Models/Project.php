<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'status', 'user_id', 'imgFile', 'budget', 'type'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
