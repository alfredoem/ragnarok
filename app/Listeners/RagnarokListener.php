<?php

namespace Alfredoem\Ragnarok\Listeners;

use Alfredoem\Ragnarok\Events\LoginAttemptEvent;

class RagnarokListener {

    public function subscribe($events)
    {
        $events->listen('login.success', LoginAttemptEvent::class, 'user');
        $events->listen(LoginAttemptEvent::class, LoginLogListener::class);
    }
}