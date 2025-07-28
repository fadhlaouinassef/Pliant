<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentaireReclamation extends Model
{
    use HasFactory;

    protected $table = 'commentaire_reclamations';

    protected $fillable = [
        'id_reclamation',
        'id_ecrivain',
        'commentaire',
    ];

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'id_reclamation');
    }

    public function ecrivain()
    {
        return $this->belongsTo(User::class, 'id_ecrivain');
    }
}