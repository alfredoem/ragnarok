<?php

namespace Alfredoem\Ragnarok\Listeners;

use Alfredoem\Ragnarok\Events\LoginAttemptEvent;
use Illuminate\Support\Facades\Log;

class LoginLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoginAttemptEvent  $event
     * @return void
     */
    public function handle(LoginAttemptEvent $event)
    {
        // Implementar aqui la llamada al servicio|transacion que creara el log de intentos de session
        Log::info("The user {$event->userRagnarok->firstName} {$event->userRagnarok->lastName} has login successfully");
    }
}