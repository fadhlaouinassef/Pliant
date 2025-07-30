<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal pour les notifications de commentaires sur les réclamations
Broadcast::channel('reclamation.{userId}', function ($user, $userId) {
    \Log::info('Channel authorization attempt', [
        'channel' => 'reclamation.'.$userId,
        'requesting_user_id' => $user->id,
        'channel_user_id' => $userId,
        'authorized' => (int) $user->id === (int) $userId
    ]);
    return (int) $user->id === (int) $userId;
});

// Canal fictif pour les cas où la reclamation n'a pas d'id_citoyen
Broadcast::channel('null-channel', function ($user) {
    return false; // Personne ne peut s'abonner à ce canal
});
