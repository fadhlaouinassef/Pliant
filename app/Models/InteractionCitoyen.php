<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteractionCitoyen extends Model
{
    protected $table = 'interaction_citoyen';
    protected $fillable = ['id_utilisateur', 'id_reclamation', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'id_reclamation');
    }
}