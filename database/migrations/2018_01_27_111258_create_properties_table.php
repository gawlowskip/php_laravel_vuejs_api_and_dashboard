<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->string('editing_hash')->nullable();
            $table->string('bricklayer')->nullable();
            $table->string('carpenter')->nullable();
            $table->string('electrician')->nullable();
            $table->string('vvs')->nullable();
            $table->string('entrepreneur')->nullable();
            $table->integer('developer_id')->unsigned();
            $table->foreign('developer_id')->references('id')->on('users');
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
        Schema::dropIfExists('properties');
    }
}
