<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserMetaStuff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('user_meta')
            ->whereIn('meta_key', ['admin_contact', 'verify'])
            ->delete()
            ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no going back
    }
}
