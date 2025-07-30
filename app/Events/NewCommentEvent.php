<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\CommentaireReclamation;
use App\Models\User;

class NewCommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $user;
    public $reclamation;

    /**
     * Create a new event instance.
     */
    public function __construct(CommentaireReclamation $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
        
        // Explicitement charger la relation reclamation si elle n'est pas déjà chargée
        if (!$comment->relationLoaded('reclamation')) {
            $comment->load('reclamation');
        }
        $this->reclamation = $comment->reclamation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Vérifier si la reclamation existe et a un id_citoyen
        if ($this->reclamation && $this->reclamation->id_citoyen) {
            \Log::info('Broadcasting to channel: reclamation.'.$this->reclamation->id_citoyen);
            return [
                new PrivateChannel('reclamation.'.$this->reclamation->id_citoyen),
            ];
        }
        
        // Si la réclamation n'existe pas ou n'a pas d'id_citoyen, retourner un canal fictif
        // Cela évitera l'erreur mais n'enverra pas de notification
        return [
            new PrivateChannel('null-channel'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'new.comment';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // Vérifier si la reclamation existe
        if ($this->reclamation) {
            return [
                'id' => $this->comment->id,
                'commentaire' => $this->comment->commentaire,
                'user_name' => $this->user->nom . ' ' . ($this->user->prenom ?? ''),
                'reclamation_id' => $this->reclamation->id,
                'reclamation_titre' => $this->reclamation->titre,
                'created_at' => $this->comment->created_at->format('d/m/Y H:i'),
            ];
        }
        
        // Si la réclamation n'existe pas, retourner des données minimales
        return [
            'id' => $this->comment->id,
            'commentaire' => $this->comment->commentaire,
            'user_name' => $this->user->nom . ' ' . ($this->user->prenom ?? ''),
            'reclamation_id' => $this->comment->id_reclamation,
            'reclamation_titre' => 'Réclamation',
            'created_at' => $this->comment->created_at->format('d/m/Y H:i'),
        ];
    }
}
