<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReclamationStatusUpdated;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\DB;

class ReclamationController extends Controller
{
    /**
     * Display a listing of the reclamations.
     */
    public function index()
    {
        $reclamations = Reclamation::where('id_citoyen', Auth::id())
            ->with('agent')
            ->latest()
            ->get();
            
        return view('citoyen.dashboard', compact('reclamations'));
    }

    /**
     * Display a listing of all reclamations for agents.
     */
    public function indexForAgent()
    {
        // Récupération de toutes les réclamations avec informations sur les citoyens et les agents
        $reclamations = Reclamation::with(['agent', 'citoyen'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Formatage des données pour la vue
        foreach ($reclamations as $reclamation) {
            // Définir le nom du citoyen si disponible
            if ($reclamation->citoyen) {
                $reclamation->nom_citoyen = $reclamation->citoyen->nom . ' ' . ($reclamation->citoyen->prenom ?? '');
            } else {
                $reclamation->nom_citoyen = 'Utilisateur inconnu';
            }
        }
            
        return view('agent.reclamations', compact('reclamations'));
    }

    /**
     * Store a newly created reclamation in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priorite' => 'required|in:faible,moyenne,elevee',
            'fichier' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
        ]);

        // Gestion du fichier (si fourni)
        $filePath = null;
        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('reclamations', 'public');
        }

        // Création de la réclamation
        Reclamation::create([
            'id_citoyen' => Auth::id(),
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'status' => 'en attente',
            'priorite' => $validated['priorite'],
            'fichier' => $filePath,
            'agent_id' => null,
            'satisfaction_citoyen' => null,
            'resolved_at' => null,
        ]);

        return redirect()->back()->with('success', 'Réclamation ajoutée avec succès.');
    }

    /**
     * Update the specified reclamation in storage.
     */
    public function update(Request $request, Reclamation $reclamation)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la réclamation
        if ($reclamation->id_citoyen != Auth::id()) {
            abort(403);
        }

        // Validation des données
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priorite' => 'required|in:faible,moyenne,elevee',
            'fichier' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
        ]);

        // Gestion du fichier (si fourni)
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier s'il existe
            if ($reclamation->fichier) {
                Storage::disk('public')->delete($reclamation->fichier);
            }
            $filePath = $request->file('fichier')->store('reclamations', 'public');
        } else {
            $filePath = $reclamation->fichier;
        }

        // Mise à jour de la réclamation
        $reclamation->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'priorite' => $validated['priorite'],
            'fichier' => $filePath,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Réclamation mise à jour avec succès.');
    }

    /**
     * Update the reclamation status and related fields for agent processing.
     */
    public function traitement_agent(Request $request, Reclamation $reclamation)
    {
        \Log::info('Début de traitement_agent', [
            'reclamation_id' => $reclamation->id,
            'user_id' => Auth::id(),
            'current_status' => $reclamation->status
        ]);

        try {
            // Validation des données
            $validated = $request->validate([
                'status' => 'required|in:en attente,en cours,résolue,rejetée',
            ]);

            \Log::debug('Validation réussie', ['new_status' => $validated['status']]);

            // Préparation des données de mise à jour
            $updateData = [
                'status' => $validated['status'],
                'agent_id' => Auth::id(),
                'updated_at' => now(),
            ];

            // Gestion de la date de résolution
            if ($validated['status'] === 'résolue') {
                $updateData['resolved_at'] = now();
                \Log::debug('Réclamation marquée comme résolue');
            } else {
                $updateData['resolved_at'] = null;
            }

            // Mise à jour de la réclamation
            $reclamation->update($updateData);
            \Log::info('Réclamation mise à jour', [
                'new_status' => $validated['status'],
                'updated_fields' => array_keys($updateData)
            ]);

            // Récupération des infos du citoyen via UtilisateurController
            $utilisateurController = new UtilisateurController();
            $citoyenName = $utilisateurController->getNomById($reclamation->id_citoyen);
            $citoyenEmail = $utilisateurController->getEmailById($reclamation->id_citoyen);
            $agentName = Auth::user()->nom . ' ' . (Auth::user()->prenom ?? '');

            if ($citoyenEmail && $citoyenName) {
                try {
                    \Log::info('Tentative d\'envoi d\'email', ['email' => $citoyenEmail]);
                    Mail::to($citoyenEmail)->send(new ReclamationStatusUpdated($reclamation, $citoyenName, $agentName));
                    \Log::info('Email envoyé avec succès', ['email' => $citoyenEmail]);
                } catch (\Exception $e) {
                    \Log::error('Échec de l\'envoi d\'email', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } else {
                \Log::warning('Citoyen non trouvé pour email', ['id_citoyen' => $reclamation->id_citoyen]);
            }

            // Réponse JSON
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'new_status' => $validated['status'],
                'updated_at' => now()->format('d/m/Y H:i')
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans traitement_agent (non-email)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'reclamation_id' => $reclamation->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store feedback for a reclamation.
     */
    public function feedback(Request $request, Reclamation $reclamation)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la réclamation
        if ($reclamation->id_citoyen != Auth::id()) {
            abort(403);
        }

        // Vérifier que la réclamation a un agent assigné
        if (!$reclamation->agent_id) {
            abort(403, 'Aucun agent assigné à cette réclamation.');
        }

        // Validation des données
        $validated = $request->validate([
            'satisfaction_citoyen' => 'required|integer|min:1|max:5',
        ]);

        $reclamation->update([
            'satisfaction_citoyen' => $validated['satisfaction_citoyen'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Évaluation enregistrée avec succès.');
    }

    /**
     * Remove the specified reclamation from storage.
     */
    public function destroy(Reclamation $reclamation)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la réclamation
        if ($reclamation->id_citoyen != Auth::id()) {
            abort(403);
        }

        // Supprimer le fichier associé s'il existe
        if ($reclamation->fichier) {
            Storage::disk('public')->delete($reclamation->fichier);
        }

        $reclamation->delete();

        return redirect()->back()->with('success', 'Réclamation supprimée avec succès.');
    }
}