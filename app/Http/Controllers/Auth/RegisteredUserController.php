<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:utilisateurs'],
            'num_tlph' => ['required', 'string', 'max:20'],
            'adresse' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
            'image' => ['nullable', 'image', 'max:2048'],
            'role' => ['required', 'in:user,agent,admin'],
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Unique name with timestamp
            $image->move(public_path('images'), $imageName); // Store in public/images
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'num_tlph' => $request->num_tlph,
            'adresse' => $request->adresse,
            'mdp' => $request->password, // Will be hashed by setMdpAttribute
            'image' => $imageName, // Store only the file name
            'role' => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard'); // Adjust as needed
    }
}