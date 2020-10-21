<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameImagesTableToPropertyImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('images', 'property_images');

        Schema::table('property_images', function (Blueprint $table) {
            $table->dropForeign('images_property_id_foreign');
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
        Schema::rename('property_images', 'images');

        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign('property_images_property_id_foreign');
            $table->foreign('property_id')->references('id')->on('properties');
        });
    }
}
