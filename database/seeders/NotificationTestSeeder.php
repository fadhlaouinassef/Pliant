<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reclamation;
use App\Models\CommentaireReclamation;
use App\Models\Notification;
use App\Events\NewCommentEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les notifications existantes
        Notification::truncate();
        $this->command->info('Notifications existantes supprimées');
        
        // Trouver ou créer un utilisateur citoyen
        $citoyen = User::firstOrCreate(
            ['email' => 'citoyen@test.com'],
            [
                'nom' => 'Citoyen',
                'prenom' => 'Test',
                'mdp' => 'password',
                'role' => 'citoyen',
                'adresse' => 'Adresse de test',
                'num_tlph' => '0123456789'
            ]
        );
        
        // Trouver ou créer un utilisateur agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@test.com'],
            [
                'nom' => 'Agent',
                'prenom' => 'Test',
                'mdp' => 'password',
                'role' => 'agent',
                'adresse' => 'Adresse de test',
                'num_tlph' => '9876543210'
            ]
        );
        
        // Vérifier s'il existe déjà une réclamation pour ce citoyen
        $reclamation = Reclamation::where('id_citoyen', $citoyen->id)->first();
        
        if (!$reclamation) {
            // Créer une réclamation
            $reclamation = Reclamation::create([
                'titre' => 'Réclamation de test',
                'description' => 'Ceci est une réclamation de test',
                'id_citoyen' => $citoyen->id,
                'agent_id' => $agent->id,
                'status' => 'en_cours',
                'priorite' => 'moyenne'
            ]);
            
            $this->command->info('Réclamation créée avec succès !');
        } else {
            $this->command->info('Réclamation existante trouvée: ID=' . $reclamation->id);
        }
        
        // Scénario 1: Citoyen commente sa propre réclamation - Ne devrait pas recevoir de notif
        $this->command->info('--- SCÉNARIO 1: Citoyen commente sa propre réclamation ---');
        $commentaireCitoyen = CommentaireReclamation::create([
            'commentaire' => 'Commentaire du citoyen sur sa propre réclamation à ' . now(),
            'id_ecrivain' => $citoyen->id,
            'id_reclamation' => $reclamation->id
        ]);
        
        event(new NewCommentEvent($commentaireCitoyen, $citoyen));
        $this->command->info('Événement NewCommentEvent déclenché pour le citoyen');
        
        sleep(1);
        
        // Scénario 2: Agent commente une réclamation qu'il traite - Ne devrait pas recevoir de notif
        $this->command->info('--- SCÉNARIO 2: Agent commente une réclamation qu\'il traite ---');
        $commentaireAgent = CommentaireReclamation::create([
            'commentaire' => 'Commentaire de l\'agent sur la réclamation qu\'il traite à ' . now(),
            'id_ecrivain' => $agent->id,
            'id_reclamation' => $reclamation->id
        ]);
        
        event(new NewCommentEvent($commentaireAgent, $agent));
        $this->command->info('Événement NewCommentEvent déclenché pour l\'agent');
        
        sleep(1);
        
        // Afficher les notifications créées
        $notifications = Notification::all();
        
        $this->command->info('Nombre total de notifications créées: ' . $notifications->count());
        
        foreach ($notifications as $notification) {
            $destinataire = User::find($notification->id_utilisateur);
            $destinataireName = $destinataire ? ($destinataire->nom . ' ' . $destinataire->prenom) : 'Inconnu';
            $this->command->info('Notification ID=' . $notification->id . 
                               ', Pour=' . $destinataireName . ' (ID=' . $notification->id_utilisateur . ')' .
                               ', Message=' . $notification->message);
        }
    }
}
