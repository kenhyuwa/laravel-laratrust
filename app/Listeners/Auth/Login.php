<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login as Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Login extends AbstractListener
{
    protected static $__CLASS__ = __CLASS__;
    
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
        parent::handle($event, static::$__CLASS__);
    }
}
