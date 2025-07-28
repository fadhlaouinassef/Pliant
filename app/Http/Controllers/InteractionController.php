<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\InteractionCitoyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::where('id_citoyen', Auth::id())
            ->with('agent')
            ->withCount(['interactions as total_aime' => function($query) {
                $query->where('type', 'aime');
            }])
            ->withCount(['interactions as total_pas_aime' => function($query) {
                $query->where('type', 'pas_aime');
            }])
            ->get();
            
        return view('citoyen.interactions', compact('reclamations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_reclamation' => 'required|exists:reclamations,id',
            'type' => 'required|in:aime,pas_aime',
        ]);

        $existingInteraction = InteractionCitoyen::where('id_utilisateur', Auth::id())
            ->where('id_reclamation', $validated['id_reclamation'])
            ->first();

        if ($existingInteraction) {
            return response()->json(['message' => 'Interaction déjà existante.'], 400);
        }

        InteractionCitoyen::create([
            'id_utilisateur' => Auth::id(),
            'id_reclamation' => $validated['id_reclamation'],
            'type' => $validated['type'],
        ]);

        $reclamation = Reclamation::find($validated['id_reclamation']);
        $totalAime = $reclamation->interactions()->where('type', 'aime')->count();
        $totalPasAime = $reclamation->interactions()->where('type', 'pas_aime')->count();

        return response()->json([
            'message' => 'Interaction enregistrée avec succès.',
            'type' => $validated['type'],
            'total_aime' => $totalAime,
            'total_pas_aime' => $totalPasAime
        ]);
    }

    public function update(Request $request, $id_reclamation)
    {
        $validated = $request->validate([
            'type' => 'required|in:aime,pas_aime',
        ]);

        $interaction = InteractionCitoyen::where('id_utilisateur', Auth::id())
            ->where('id_reclamation', $id_reclamation)
            ->firstOrFail();

        $interaction->update([
            'type' => $validated['type'],
        ]);

        $reclamation = Reclamation::find($id_reclamation);
        $totalAime = $reclamation->interactions()->where('type', 'aime')->count();
        $totalPasAime = $reclamation->interactions()->where('type', 'pas_aime')->count();

        return response()->json([
            'message' => 'Interaction mise à jour avec succès.',
            'type' => $validated['type'],
            'total_aime' => $totalAime,
            'total_pas_aime' => $totalPasAime
        ]);
    }

    public function destroy($id_reclamation)
    {
        $interaction = InteractionCitoyen::where('id_utilisateur', Auth::id())
            ->where('id_reclamation', $id_reclamation)
            ->firstOrFail();

        $interaction->delete();

        $reclamation = Reclamation::find($id_reclamation);
        $totalAime = $reclamation->interactions()->where('type', 'aime')->count();
        $totalPasAime = $reclamation->interactions()->where('type', 'pas_aime')->count();

        return response()->json([
            'message' => 'Interaction supprimée avec succès.',
            'type' => null,
            'total_aime' => $totalAime,
            'total_pas_aime' => $totalPasAime
        ]);
    }

    public function getInteraction($id_reclamation)
    {
        $interaction = InteractionCitoyen::where('id_utilisateur', Auth::id())
            ->where('id_reclamation', $id_reclamation)
            ->first();

        return response()->json($interaction ? ['type' => $interaction->type] : ['type' => null]);
    }
}