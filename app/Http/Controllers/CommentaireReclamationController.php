<?php

namespace App\Http\Controllers;

use App\Models\CommentaireReclamation;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\NewCommentEvent;

class CommentaireReclamationController extends Controller
{
    public function index(Reclamation $reclamation)
    {
        Log::info('Fetching comments for reclamation: ' . $reclamation->id);
        
        try {
            $commentaires = $reclamation->commentaires()
                ->with('ecrivain')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'commentaire' => $comment->commentaire,
                        'created_at' => $comment->created_at->format('d/m/Y H:i'),
                        'id_ecrivain' => $comment->id_ecrivain,
                        'can_delete' => $comment->id_ecrivain == Auth::id()
                    ];
                });

            return response()->json($commentaires);
        } catch (\Exception $e) {
            Log::error('Error fetching comments: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_reclamation' => 'required|exists:reclamations,id',
            'commentaire' => 'required|string|max:1000',
        ]);

        try {
            $comment = CommentaireReclamation::create([
                'id_reclamation' => $validated['id_reclamation'],
                'id_ecrivain' => Auth::id(),
                'commentaire' => $validated['commentaire'],
            ]);
            
            // Récupérer la réclamation associée avec le chargement explicite
            $reclamation = Reclamation::find($validated['id_reclamation']);
            
            // S'assurer que la relation est correctement chargée pour l'événement
            $comment->reclamation = $reclamation;
            
            // Variable pour suivre si nous devons envoyer une notification
            $shouldSendNotification = false;
            
            // Conditions pour envoyer une notification au citoyen propriétaire:
            // 1. La réclamation existe
            // 2. La réclamation a un propriétaire (id_citoyen)
            // 3. Le commentaire n'est pas écrit par le propriétaire de la réclamation
            if ($reclamation && 
                $reclamation->id_citoyen && 
                $reclamation->id_citoyen != Auth::id()) {
                
                $shouldSendNotification = true;
                
                // Ajouter des logs pour le débogage
                \Log::info('Sending notification to citoyen for comment', [
                    'comment_id' => $comment->id,
                    'reclamation_id' => $reclamation->id,
                    'recipient_id' => $reclamation->id_citoyen,
                    'sender_id' => Auth::id()
                ]);
            }
            
            // Conditions pour envoyer une notification à l'agent assigné:
            // 1. La réclamation existe
            // 2. La réclamation a un agent assigné (agent_id)
            // 3. Le commentaire n'est pas écrit par l'agent assigné
            if ($reclamation && 
                $reclamation->agent_id && 
                $reclamation->agent_id != Auth::id()) {
                
                $shouldSendNotification = true;
                
                // Ajouter des logs pour le débogage
                \Log::info('Sending notification to agent for comment', [
                    'comment_id' => $comment->id,
                    'reclamation_id' => $reclamation->id,
                    'recipient_agent_id' => $reclamation->agent_id,
                    'sender_id' => Auth::id()
                ]);
            }
            
            // Déclencher l'événement de notification si nécessaire
            if ($shouldSendNotification) {
                event(new NewCommentEvent($comment, Auth::user()));
            } else {
                \Log::info('No notification sent for comment', [
                    'comment_id' => $comment->id,
                    'reclamation_id' => $reclamation->id ?? 'null',
                    'has_id_citoyen' => $reclamation ? (bool)$reclamation->id_citoyen : false,
                    'is_self_comment' => $reclamation ? ($reclamation->id_citoyen == Auth::id()) : false
                ]);
            }
            
            return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout du commentaire: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du commentaire.');
        }
    }

    public function destroy(CommentaireReclamation $comment)
    {
        if ($comment->id_ecrivain !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }
}