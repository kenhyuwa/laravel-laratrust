<?php 

namespace App\Listeners\Auth;

class AbstractListener
{
	/**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event, string $type)
    {
        $event->user->userLogs()->create([
            'type' => $type,
            'logs' => $event->user,
            'ip_address' => request()->getClientIp(),
            'browser' => request()->header('User-Agent')
        ]);
    }
}