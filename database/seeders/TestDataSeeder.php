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

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        
        // Créer un commentaire pour tester les notifications
        $commentaire = CommentaireReclamation::create([
            'commentaire' => 'Ceci est un nouveau commentaire de test à ' . now(),
            'id_ecrivain' => $citoyen->id,
            'id_reclamation' => $reclamation->id
        ]);
        
        $this->command->info('Commentaire créé avec succès !');
        
        // Déclencher manuellement l'événement qui crée les notifications
        event(new NewCommentEvent($commentaire, $citoyen));
        
        $this->command->info('Événement NewCommentEvent déclenché manuellement');
        
        // Vérifier que les notifications sont bien créées
        sleep(2); // Attendre un peu pour s'assurer que l'événement a eu le temps de s'exécuter
        
        $notifications = Notification::where('id_reclamation', $reclamation->id)->get();
        
        $this->command->info('Nombre de notifications trouvées: ' . $notifications->count());
        
        foreach ($notifications as $notification) {
            $this->command->info('Notification ID=' . $notification->id . ', Pour=' . $notification->id_utilisateur . ', Message=' . $notification->message);
        }
    }
}
