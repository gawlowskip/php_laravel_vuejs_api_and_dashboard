<?php

use App\Session;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Session::class, function (Faker $faker) {
    $now = Carbon::now();
    $lastActivity = $now->subDays(rand(0, 30))->timestamp;

    return [
        'id' => $faker->uuid,
        'type' => $faker->randomElement([
            Session::TYPE_WEB,
            Session::TYPE_API
        ]),
        'user_id' => $faker->randomElement([
            null,
            $faker->numberBetween(1, User::count())
        ]),
        'ip_address' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'payload' => $faker->sha256,
        'last_activity' => $lastActivity
    ];
});
