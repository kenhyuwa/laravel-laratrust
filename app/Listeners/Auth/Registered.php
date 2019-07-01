<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Registered as Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class Registered extends SendEmailVerificationNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        parent::handle($event);
    }
}
