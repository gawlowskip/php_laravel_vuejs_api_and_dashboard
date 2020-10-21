<?php

use App\Agreement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* TODO: Change agreements into developer_agreements */
        Schema::create('agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('developer_id')->unsigned();
            $table->foreign('developer_id')->references('id')->on('users');
            $table->dateTime('from')->nullable()->comment('DATETIME');
            $table->dateTime('to')->nullable()->comment('DATETIME');
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('verified')->default(Agreement::UNVERIFIED_AGREEMENT);
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
        Schema::dropIfExists('agreements');
    }
}
