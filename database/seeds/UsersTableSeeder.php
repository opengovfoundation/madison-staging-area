<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $creds = Config::get('madison.seeder');

        DB::table('users')->insert([
            'email' => $creds['user_email'],
            'password' => Hash::make($creds['user_password']),
            'fname' => $creds['user_fname'],
            'lname' => $creds['user_lname'],
            'token' => '',
        ]);

        DB::table('users')->insert([
            'email' => $creds['admin_email'],
            'password' => Hash::make($creds['admin_password']),
            'fname' => $creds['admin_fname'],
            'lname' => $creds['admin_lname'],
            'token' => '',
        ]);

        DB::table('users')->insert([
            'email' => $creds['unconfirmed_email'],
            'password' => Hash::make($creds['unconfirmed_password']),
            'fname' => $creds['unconfirmed_fname'],
            'lname' => $creds['unconfirmed_lname'],
            'token' => '12345',
        ]);

        $adminUser = User::where('email', $creds['admin_email'])->first();

        $events = [
            'madison.comment.created',
            'madison.feedback.seen',
            'madison.sponsor.created',
            'madison.sponsor.member-added',
            'madison.user.verification.request',
            'madison.user.verification.changed',
        ];

        // Default admin to receive all notifications
        foreach ($events as $event) {
            DB::table('notification_preferences')->insert([
                'event' => $event,
                'type' => 'email',
                'user_id' => $adminUser->id,
            ]);
        }

    }
}
