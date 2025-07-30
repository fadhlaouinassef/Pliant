<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $table = 'notifications';
    
    protected $fillable = [
        'id_utilisateur',
        'id_reclamation',
        'message',
        'type',
        'etat',
        'data'
    ];
    
    /**
     * Scope pour obtenir uniquement les notifications de l'utilisateur connecté
     */
    public function scopeForCurrentUser($query)
    {
        return $query->where('id_utilisateur', auth()->id());
    }
    
    protected $casts = [
        'etat' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
    
    /**
     * Relation avec la réclamation
     */
    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'id_reclamation');
    }
}
