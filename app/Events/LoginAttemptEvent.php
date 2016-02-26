<?php

namespace Alfredoem\Ragnarok\Events;

use Alfredoem\Ragnarok\SecUsers\SecUser;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class LoginAttemptEvent extends Event
{
    use SerializesModels;

    public $userRagnarok;

    /**
     * Create a new event instance.
     *
     * @param  SecUser  $userRagnarok
     * @return void
     */
    public function __construct(SecUser $userRagnarok)
    {
        $this->userRagnarok = $userRagnarok;
    }
}