<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications de l'utilisateur connecté
     */
    public function index()
    {
        try {
            $notifications = Auth::user()->notifications()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->message,
                        'reclamation_id' => $notification->id_reclamation,
                        'etat' => $notification->etat,
                        'created_at' => $notification->created_at->format('d/m/Y H:i'),
                        'data' => $notification->data
                    ];
                });

            return response()->json($notifications);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des notifications.'], 500);
        }
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            
            // Vérifier que la notification appartient bien à l'utilisateur connecté
            if ($notification->id_utilisateur !== Auth::id()) {
                return response()->json(['error' => 'Vous n\'êtes pas autorisé à modifier cette notification.'], 403);
            }
            
            $notification->etat = true;
            $notification->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de la notification comme lue: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue.'], 500);
        }
    }
    
    /**
     * Marquer toutes les notifications de l'utilisateur comme lues
     */
    public function markAllAsRead()
    {
        try {
            Auth::user()->notifications()->update(['etat' => true]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage de toutes les notifications comme lues: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue.'], 500);
        }
    }
}
