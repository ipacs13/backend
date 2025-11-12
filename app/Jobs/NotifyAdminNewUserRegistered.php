<?php

namespace App\Jobs;

use App\Mail\NewUserRegisteredNotification;
use App\Models\User;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewUserRegistered implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all admin users
        $adminUsers = User::role(Role::ROLE_ADMIN)->get();

        // Send email to each admin
        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new NewUserRegisteredNotification($this->user));
        }
    }
}
