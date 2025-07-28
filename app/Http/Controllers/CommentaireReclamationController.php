<?php

namespace App\Http\Controllers;

use App\Models\CommentaireReclamation;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        CommentaireReclamation::create([
            'id_reclamation' => $validated['id_reclamation'],
            'id_ecrivain' => Auth::id(),
            'commentaire' => $validated['commentaire'],
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
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