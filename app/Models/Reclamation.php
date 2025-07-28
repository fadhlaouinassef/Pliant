<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $table = 'reclamations';

    protected $fillable = [
        'id_citoyen',
        'titre',
        'description',
        'status',
        'priorite',
        'agent_id',
        'fichier',
        'satisfaction_citoyen'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resolved_at' => 'datetime',
        'satisfaction_citoyen' => 'integer'
    ];

    /**
     * Relation avec le citoyen (utilisateur qui a soumis la réclamation)
     */
    public function citoyen()
    {
        return $this->belongsTo(User::class, 'id_citoyen');
    }

    public function commentaires()
    {
        return $this->hasMany(CommentaireReclamation::class, 'id_reclamation');
    }

    /**
     * Relation avec l'agent (utilisateur qui a traité la réclamation)
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function interactions()
    {
        return $this->hasMany(InteractionCitoyen::class, 'id_reclamation');
    }
    
}