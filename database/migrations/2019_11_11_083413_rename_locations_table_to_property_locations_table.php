<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLocationsTableToPropertyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('locations', 'property_locations');

        Schema::table('property_locations', function (Blueprint $table) {
            $table->dropForeign('locations_property_id_foreign');
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
        Schema::rename('property_locations', 'locations');

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('property_locations_property_id_foreign');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
}
