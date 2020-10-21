<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* TODO: Change ads into developer_ads */
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->boolean('active')->nullable();
            $table->string('image')->nullable();
            $table->string('external_image_url')->nullable();
            $table->string('url')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('price_lead', 10, 2)->nullable();
            $table->integer('seconds')->nullable();
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
        Schema::dropIfExists('ads');
    }
}
