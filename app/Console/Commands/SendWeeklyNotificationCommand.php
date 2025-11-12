<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendWeeklyNotificationToClients;
use Exception;

class SendWeeklyNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cclpi:send-weekly-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly notification to clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Sending weekly notification to clients...');
            $this->exec();
            $this->info('Weekly notification sent successfully');
            return Command::SUCCESS;
        } catch (Exception $e) {
            activity('commands[send-weekly-notification]')
                ->withProperties([
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ])
                ->log($e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Weekly notification sent successfully');
    }

    private function exec(): void
    {
        $users = User::role(Role::ROLE_USER)->get();

        $this->output->progressStart(count($users));

        foreach ($users as $user) {
            Mail::to($user->email)->send(new SendWeeklyNotificationToClients());
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
