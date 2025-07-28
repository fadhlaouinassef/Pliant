<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User; // Add this to use the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function index()
    {
        return view('agent.dashboard');
    }

    public function reclamations()
    {
        // Fetch reclamations assigned to the authenticated agent or unassigned reclamations
        $reclamations = Reclamation::where('agent_id', Auth::id())
            ->orWhereNull('agent_id')
            ->get();

        return view('agent.reclamations', compact('reclamations'));
    }

    public function coéquipiers()
    {
        $agents = User::where('role', 'agent')->paginate(9);
        return view('agent.coéquipiers', compact('agents'));
    }
}