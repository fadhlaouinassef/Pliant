<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avis = Avis::orderBy('created_at', 'desc')->get();
        return view('admin.avis', compact('avis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Non utilisé, le formulaire est dans la page d'accueil
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_utilisateur' => 'required|string|max:255',
            'note' => 'required|integer|between:1,5',
            'commentaire' => 'required|string'
        ]);

        Avis::create([
            'nom_utilisateur' => $request->nom_utilisateur,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'etat' => 'non_visible'
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('home')->with('success', 'Merci pour votre avis!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Avis $avis)
    {
        // Non utilisé
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Avis $avis)
    {
        // Non utilisé
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Avis $avis)
    {
        $request->validate([
            'etat' => 'required|in:visible,non_visible'
        ]);

        $avis->etat = $request->etat;
        $avis->save();

        return redirect()->route('admin.avis')->with('success', 'État de l\'avis mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avis $avis)
    {
        $avis->delete();
        return redirect()->route('admin.avis')->with('success', 'Avis supprimé avec succès!');
    }
}
