<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_utilisateur',
        'note',
        'commentaire',
        'etat'
    ];

    protected $casts = [
        'note' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}