<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrialStartsAtAndTrialEndsAtColumnsToAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dateTime('trial_starts_at')->nullable()->after('to')->comment('DATETIME');
            $table->dateTime('trial_ends_at')->nullable()->after('trial_starts_at')->comment('DATETIME');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropColumn('trial_starts_at');
            $table->dropColumn('trial_ends_at');
        });
    }
}