<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedToSponsorNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('notification_preferences')
            ->where('event', 'madison.sponsor.member-added')
            ->update(['event' => 'madison.user.added_to_sponsor'])
            ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('notification_preferences')
            ->where('event', 'madison.user.added_to_sponsor')
            ->update(['event' => 'madison.sponsor.member-added'])
            ;
    }
}
