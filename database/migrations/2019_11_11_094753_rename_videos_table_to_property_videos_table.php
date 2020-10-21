<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameVideosTableToPropertyVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('videos', 'property_videos');

        Schema::table('property_videos', function (Blueprint $table) {
            $table->dropForeign('videos_property_id_foreign');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('property_videos', 'videos');

        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign('property_videos_property_id_foreign');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
}
