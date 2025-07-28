<!DOCTYPE html>
<html>
<head>
    <title>Mise à jour de votre réclamation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9fafb; }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
        }
        .status-en-attente { background-color: #f59e0b; }
        .status-en-cours { background-color: #3b82f6; }
        .status-résolue { background-color: #10b981; }
        .status-rejetée { background-color: #ef4444; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mise à jour de votre réclamation</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $citoyenName }},</p>
            
            <p>Le statut de votre réclamation a été mis à jour :</p>
            
            <p><strong>Titre :</strong> {{ $reclamation->titre }}</p>
            <p><strong>Référence :</strong> #{{ $reclamation->id }}</p>
            
            <p><strong>Nouveau statut :</strong> 
                <span class="status status-{{ str_replace(' ', '-', strtolower($reclamation->status)) }}">
                    {{ ucfirst($reclamation->status) }}
                </span>
            </p>
            
            <p><strong>Mis à jour par :</strong> {{ $agentName }}</p>
            <p><strong>Date de mise à jour :</strong> {{ $reclamation->updated_at->format('d/m/Y H:i') }}</p>
            
            <p>Vous pouvez consulter les détails de votre réclamation en vous connectant à votre espace citoyen.</p>
            
            <p>Cordialement,<br>L'équipe de support</p>
        </div>
        
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
        </div>
    </div>
</body>
</html>