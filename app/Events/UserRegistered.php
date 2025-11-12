<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserRegistered
{
    use SerializesModels;

    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
