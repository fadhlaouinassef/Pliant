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
use App\Models\Notification;

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
        
        // Enregistrer la notification en base de données
        $this->storeNotifications();
    }
    
    /**
     * Enregistrer les notifications en base de données
     */
    protected function storeNotifications()
    {
        try {
            \Log::info('Tentative d\'enregistrement de notification pour commentaire #' . $this->comment->id);
            \Log::info('Détails réclamation: ID=' . ($this->reclamation->id ?? 'NULL') . 
                      ', Citoyen ID=' . ($this->reclamation->id_citoyen ?? 'NULL') . 
                      ', Agent ID=' . ($this->reclamation->agent_id ?? 'NULL'));
            \Log::info('Auteur du commentaire ID=' . $this->comment->id_ecrivain);
            \Log::info('Utilisateur connecté ID=' . $this->user->id);
            
            // Vérifier si l'utilisateur citoyen existe
            if ($this->reclamation && $this->reclamation->id_citoyen) {
                $userExists = \App\Models\User::where('id', $this->reclamation->id_citoyen)->exists();
                \Log::info('Utilisateur citoyen ID=' . $this->reclamation->id_citoyen . ' existe: ' . ($userExists ? 'Oui' : 'Non'));
            }
            
            // Vérifier si l'agent existe
            if ($this->reclamation && $this->reclamation->agent_id) {
                $agentExists = \App\Models\User::where('id', $this->reclamation->agent_id)->exists();
                \Log::info('Agent ID=' . $this->reclamation->agent_id . ' existe: ' . ($agentExists ? 'Oui' : 'Non'));
            }
            
            // Ne pas notifier l'auteur du commentaire
            $commentAuthorId = $this->comment->id_ecrivain;
            
            // Si la réclamation existe et a un propriétaire différent de l'auteur du commentaire
            if ($this->reclamation && 
                $this->reclamation->id_citoyen && 
                $this->reclamation->id_citoyen != $commentAuthorId) {
                
                // Vérifier si l'utilisateur existe avant de créer la notification
                if (\App\Models\User::where('id', $this->reclamation->id_citoyen)->exists()) {
                    $message = $this->user->nom . ' ' . ($this->user->prenom ?? '') . ' a commenté votre réclamation';
                    
                    \Log::info('Création de notification pour citoyen ID=' . $this->reclamation->id_citoyen);
                    
                    try {
                        Notification::create([
                            'id_utilisateur' => $this->reclamation->id_citoyen,
                            'id_reclamation' => $this->reclamation->id,
                            'message' => $message,
                            'type' => 'comment',
                            'etat' => false,
                            'data' => [
                                'commentaire' => $this->comment->commentaire,
                                'user_name' => $this->user->nom . ' ' . ($this->user->prenom ?? ''),
                                'reclamation_titre' => $this->reclamation->titre
                            ]
                        ]);
                        
                        \Log::info('Notification enregistrée pour le citoyen: ' . $this->reclamation->id_citoyen);
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'enregistrement de la notification pour le citoyen: ' . $e->getMessage());
                        \Log::error($e->getTraceAsString());
                    }
                } else {
                    \Log::warning('Impossible de créer la notification: utilisateur citoyen ID=' . $this->reclamation->id_citoyen . ' introuvable');
                }
            } else {
                \Log::info('Pas de notification pour le citoyen: il est l\'auteur du commentaire ou n\'est pas lié à cette réclamation');
            }
            
            // Si la réclamation a un agent assigné différent de l'auteur du commentaire
            if ($this->reclamation && 
                $this->reclamation->agent_id && 
                $this->reclamation->agent_id != $commentAuthorId) {
                
                // Vérifier si l'agent existe avant de créer la notification
                if (\App\Models\User::where('id', $this->reclamation->agent_id)->exists()) {
                    $message = $this->user->nom . ' ' . ($this->user->prenom ?? '') . ' a commenté une réclamation que vous traitez';
                    
                    \Log::info('Création de notification pour agent ID=' . $this->reclamation->agent_id);
                    
                    try {
                        Notification::create([
                            'id_utilisateur' => $this->reclamation->agent_id,
                            'id_reclamation' => $this->reclamation->id,
                            'message' => $message,
                            'type' => 'comment',
                            'etat' => false,
                            'data' => [
                                'commentaire' => $this->comment->commentaire,
                                'user_name' => $this->user->nom . ' ' . ($this->user->prenom ?? ''),
                                'reclamation_titre' => $this->reclamation->titre
                            ]
                        ]);
                        
                        \Log::info('Notification enregistrée pour l\'agent: ' . $this->reclamation->agent_id);
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'enregistrement de la notification pour l\'agent: ' . $e->getMessage());
                        \Log::error($e->getTraceAsString());
                    }
                } else {
                    \Log::warning('Impossible de créer la notification: agent ID=' . $this->reclamation->agent_id . ' introuvable');
                }
            } else {
                \Log::info('Pas de notification pour l\'agent: il est l\'auteur du commentaire ou n\'est pas lié à cette réclamation');
            }
        } catch (\Exception $e) {
            \Log::error('Erreur générale lors de l\'enregistrement des notifications: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        $commentAuthorId = $this->comment->id_ecrivain;
        
        // Vérifier si la reclamation existe et a un id_citoyen différent de l'auteur du commentaire
        if ($this->reclamation && 
            $this->reclamation->id_citoyen && 
            $this->reclamation->id_citoyen != $commentAuthorId) {
            \Log::info('Broadcasting to citoyen channel: reclamation.'.$this->reclamation->id_citoyen);
            $channels[] = new PrivateChannel('reclamation.'.$this->reclamation->id_citoyen);
        } else {
            \Log::info('Not broadcasting to citoyen (auteur du commentaire ou pas concerné)');
        }
        
        // Vérifier si la réclamation a un agent assigné différent de l'auteur du commentaire
        if ($this->reclamation && 
            $this->reclamation->agent_id && 
            $this->reclamation->agent_id != $commentAuthorId) {
            \Log::info('Broadcasting to agent channel: agent-reclamation.'.$this->reclamation->agent_id);
            $channels[] = new PrivateChannel('agent-reclamation.'.$this->reclamation->agent_id);
        } else {
            \Log::info('Not broadcasting to agent (auteur du commentaire ou pas concerné)');
        }
        
        // Si aucun canal n'est disponible, utiliser un canal fictif
        if (empty($channels)) {
            \Log::info('No valid channels found, using null-channel');
            return [new PrivateChannel('null-channel')];
        }
        
        return $channels;
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
