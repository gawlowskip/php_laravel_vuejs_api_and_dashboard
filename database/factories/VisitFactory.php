<?php

use App\Property;
use App\User;
use App\Visit;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Visit::class, function (Faker $faker) {
    $now = Carbon::now();
    $createdAt = $now->subDays(rand(0, 30))->toDateTimeString();
    return [
        'user_id' => $faker->randomElement([
            null,
            $faker->numberBetween(1, User::count())
        ]),
        'ip_address' => $faker->ipv4,
        'visitable_id' => $faker->numberBetween(1, Property::count()),
        'visitable_type' => Property::class,
        'created_at' => $createdAt,
        'updated_at' => $createdAt
    ];
});
