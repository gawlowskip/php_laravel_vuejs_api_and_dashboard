<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('property_type');
            $table->string('material');
            $table->string('completion_date');
            $table->string('size');
            $table->integer('rooms_amount');
            $table->integer('baths_amount');
            $table->integer('bedrooms_amount');
            $table->integer('floors');
            $table->string('price');
            $table->integer('property_id')->unsigned()->unique();
            $table->foreign('property_id')->references('id')->on('properties');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
}
