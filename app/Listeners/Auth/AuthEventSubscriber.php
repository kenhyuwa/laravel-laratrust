<?php

namespace App\Listeners\Auth;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Attempting',
            'App\Listeners\Auth\Attempting@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\Authenticated',
            'App\Listeners\Auth\Authenticated@handle'
        );
        
        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'App\Listeners\Auth\Failed@handle'
        );
        
        $events->listen(
            'Illuminate\Auth\Events\Lockout',
            'App\Listeners\Auth\Lockout@handle'
        );
        
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\Auth\Login@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\Auth\Logout@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\OtherDeviceLogout',
            'App\Listeners\Auth\OtherDeviceLogout@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\PasswordReset',
            'App\Listeners\Auth\PasswordReset@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\Auth\Registered@handle'
        );

        $events->listen(
            'Illuminate\Auth\Events\Verified',
            'App\Listeners\Auth\Verified@handle'
        );
    }
}
