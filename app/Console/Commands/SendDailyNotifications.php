<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\DailyNotifications;
use Mail;

class SendDailyNotifications extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'send-daily-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes notifications that are sitting in the database to be sent as a daily digest.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        User::all()->each(function ($user) {
            if ($user->notifications()->count() > 0) {
                Mail::to($user)->send(new DailyNotifications($user));
                $user->notifications()->delete();
            }
        });
    }
}
