<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reclamation;

class CitoyenController extends Controller
{
    public function index()
    {
        return view('citoyen.dashboard');
    }

    public function reclamations()
    {
        $reclamations = Reclamation::where('id_citoyen', auth()->id())->get();
        return view('citoyen.reclamations', compact('reclamations'));
    }
}