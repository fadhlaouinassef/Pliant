<?php

namespace App\Mail;

use App\Models\Reclamation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;
    public $citoyenName;
    public $agentName;

    public function __construct(Reclamation $reclamation, string $citoyenName, ?string $agentName)
    {
        $this->reclamation = $reclamation;
        $this->citoyenName = $citoyenName;
        $this->agentName = $agentName;
    }

    public function build()
    {
        return $this->subject('Mise à jour de votre réclamation')
                    ->view('reclamation_status_updated');
    }
}