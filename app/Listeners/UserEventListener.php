<?php

namespace App\Listeners;

use App\Events\UserLogin;

class UserEventListener
{

    const ENTITY = 'user';

    public function onUserLogin($event)
    {
        activity()
            ->performedOn($event->user)
            ->causedBy($event->user)
            ->useLog('user_login')
            ->event('login')
            ->log(self::ENTITY . ' logged in');
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            UserLogin::class,
            self::class . '@onUserLogin'
        );
    }
}
