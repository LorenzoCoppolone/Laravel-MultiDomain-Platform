<?php

namespace App\Notifications\Studyroom;

use Illuminate\Auth\Notifications\VerifyEmail; // <-- IMPORTANTE: Estendiamo questa!
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends VerifyEmail
{
    /**
     * Costruisce la mail personalizzata.
     */
    public function toMail($notifiable)
    {
        // Ora questo funzionerà perfettamente perché la funzione è definita qui sotto!
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->theme('studyroom') // <-- Applica il CSS che hai creato prima!
            ->subject('Conferma il tuo account — StudyRoom')
            ->greeting('Ciao ' . $notifiable->nome . '!')
            ->line('Grazie per esserti registrato su StudyRoom. Per completare la registrazione e accedere a tutti i materiali di studio, clicca sul pulsante qui sotto:')
            ->action('Verifica Indirizzo Email', $verificationUrl)
            ->line('Se non hai creato un account su StudyRoom, puoi ignorare questa email.')
            ->salutation('Un saluto, Il team di StudyRoom');
    }

    /**
     * Genera l'URL di verifica firmato.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            // ATTENZIONE: prima era 'verification.verify', ma siccome abbiamo
            // sistemato i prefissi delle rotte, ora si chiama così:
            'verification.verify', 
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}