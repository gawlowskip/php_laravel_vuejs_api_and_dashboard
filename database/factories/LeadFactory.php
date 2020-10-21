<?php

use App\Ad;
use App\Lead;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    $user = User::find($faker->numberBetween(1, User::count()));
    $clicked_on = Carbon::createFromTimestamp(strtotime('+' . rand(10, 15) . ' days'))->toDateTimeString();
    return [
        'ad_id' => $faker->numberBetween(1, Ad::count()),
        'user_id' => $user->facebook_id ? $user->facebook_id : $user->id,
        'full_name' => $user->name . ' ' . $user->last_name,
        'email' => $user->email,
        'clicked_on' => $clicked_on,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});
