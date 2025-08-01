<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
    public function index()
    {
        try {
            $utilisateurs = User::paginate(10);
            return view('admin.utilisateurs', compact('utilisateurs'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Erreur lors du chargement des utilisateurs: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email', // Table utilisateurs
            'num_tlph' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'role' => 'required|in:citoyen,agent,admin',
            'mdp' => 'required|string|min:8',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        User::create($data); // Utilisation de User

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function update(Request $request, User $utilisateur) // Type-hint avec User
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,'.$utilisateur->id, // Table utilisateurs
            'num_tlph' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'role' => 'required|in:citoyen,agent,admin',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($utilisateur->image && file_exists(public_path('images/' . $utilisateur->image))) {
                unlink(public_path('images/' . $utilisateur->image));
            }
            
            // Enregistrer la nouvelle image
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        $utilisateur->update($data);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $utilisateur)
    {
        try {
            // Supprimer l'image de profil si elle existe
            if ($utilisateur->image && file_exists(public_path('images/' . $utilisateur->image))) {
                unlink(public_path('images/' . $utilisateur->image));
            }
            
            $utilisateur->delete();
            return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.utilisateurs.index')->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function agents()
    {
        $agents = User::where('role', 'agent')->paginate(9); // Adjust pagination as needed
        return view('admin.agents', compact('agents'));
    }

    /**
     * Retrieve the name of a user by their ID.
     */
    public function getNomById($id)
    {
        $user = User::find($id);
        return $user ? $user->nom : null;
    }

    /**
     * Retrieve the email of a user by their ID.
     */
    public function getEmailById($id)
    {
        $user = User::find($id);
        return $user ? $user->email : null;
    }
}