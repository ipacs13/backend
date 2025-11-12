<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Events\UserRegistered;
use App\Jobs\NotifyAdminNewUserRegistered;

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

    public function onUserRegistered($event)
    {
        NotifyAdminNewUserRegistered::dispatch($event->user);
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

        $events->listen(
            UserRegistered::class,
            self::class . '@onUserRegistered'
        );
    }
}
