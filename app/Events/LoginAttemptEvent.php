<?php

namespace Alfredoem\Ragnarok\Events;

use Alfredoem\Ragnarok\Soul\AuthRagnarok;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class LoginAttemptEvent extends Event
{
    use SerializesModels;

    public $userRagnarok;

    /**
     * Create a new event instance.
     *
     * @param  AuthRagnarok  $userRagnarok
     * @return void
     */
    public function __construct(AuthRagnarok $userRagnarok)
    {
        $this->userRagnarok = $userRagnarok;
    }
}