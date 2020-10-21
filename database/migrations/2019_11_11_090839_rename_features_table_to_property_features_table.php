<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFeaturesTableToPropertyFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('features', 'property_features');

        Schema::table('property_features', function (Blueprint $table) {
            $table->dropForeign('features_property_id_foreign');
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
        Schema::rename('property_features', 'features');

        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign('property_features_property_id_foreign');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
}
