<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom', 'prenom', 'email', 'num_tlph', 'adresse', 'mdp', 'image', 'role',
    ];

    protected $hidden = [
        'mdp', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mdp;
    }

    public function setMdpAttribute($value)
    {
        $this->attributes['mdp'] = bcrypt($value);
    }
    
    /**
     * Relation avec les notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_utilisateur');
    }
    
    /**
     * Récupérer les notifications non lues
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('etat', false);
    }
}
?>