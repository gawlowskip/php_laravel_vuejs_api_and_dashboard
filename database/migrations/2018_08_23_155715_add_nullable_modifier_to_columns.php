<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableModifierToColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->string('district')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->decimal('latitude')->nullable()->change();
            $table->decimal('longitude')->nullable()->change();

        });
        Schema::table('features', function (Blueprint $table) {
            $table->string('property_type')->nullable()->change();
            $table->string('material')->nullable()->change();
            $table->string('completion_date')->nullable()->change();
            $table->string('size')->nullable()->change();
            $table->integer('rooms_amount')->nullable()->change();
            $table->integer('baths_amount')->nullable()->change();
            $table->integer('bedrooms_amount')->nullable()->change();
            $table->integer('floors')->nullable()->change();
            $table->string('price')->nullable()->change();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('filename')->nullable()->change();
        });

        Schema::table('images', function (Blueprint $table) {
            $table->string('filename')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
