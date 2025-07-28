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
        $utilisateurs = User::paginate(10);
        return view('admin.utilisateurs', compact('utilisateurs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email', // Table utilisateurs
            'num_tlph' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'role' => 'required|in:utilisateur,admin',
            'mdp' => 'required|string|min:8',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('utilisateurs', 'public');
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
            'role' => 'required|in:utilisateur,admin',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($utilisateur->image) {
                Storage::disk('public')->delete($utilisateur->image);
            }
            $data['image'] = $request->file('image')->store('utilisateurs', 'public');
        }

        $utilisateur->update($data);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->image) {
            Storage::disk('public')->delete($utilisateur->image);
        }
        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès');
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