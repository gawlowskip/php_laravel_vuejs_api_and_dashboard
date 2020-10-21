<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsFromClientsTableToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('street_1')->nullable()->after('facebook_id')->comment('STRING');
            $table->string('street_2')->nullable()->after('street_1')->comment('STRING');
            $table->string('city')->nullable()->after('street_2')->comment('STRING');
            $table->string('postal_code')->nullable()->after('city')->comment('STRING');
            $table->decimal('latitude', 14, 8)->nullable()->after('postal_code')->comment('DECIMAL');
            $table->decimal('longitude', 14, 8)->nullable()->after('latitude')->comment('DECIMAL');
            $table->string('cvr_number')->nullable()->after('longitude')->comment('STRING');
            $table->tinyInteger('active')->default(User::ACTIVE)->after('cvr_number')->comment('TINYINTEGER');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'street_1',
                'street_2',
                'city',
                'postal_code',
                'latitude',
                'longitude',
                'cvr_number',
                'active'
            ]);
        });
    }
}
