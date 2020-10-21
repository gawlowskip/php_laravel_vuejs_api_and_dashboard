<?php

use App\Developer;
use Faker\Generator as Faker;
use App\User;
use Carbon\Carbon;

$factory->define(User::class, function (Faker $faker) {
    static $password;
    $name = $faker->firstName;
    $last_name = $faker->lastName;
    $facebook_id = $faker->userName . $faker->numberBetween(100000, 999999);
    $now = Carbon::now();
    $createdAt = $now->subDays(rand(0, 30))->toDateTimeString();
    return [
        'name' => $name,
        'last_name' => $last_name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'architect_name' => $faker->name,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'facebook_id' => $faker->randomElement([null, $facebook_id]),
        'street_1' => $faker->streetName,
        'street_2' => $faker->buildingNumber,
        'city' => $faker->city,
        'postal_code' => $faker->postcode,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'cvr_number' => $faker->numberBetween(10000000, 99999999),
        'active' => User::ACTIVE,
        'is_admin' => $faker->randomElement([User::IS_ADMIN, !User::IS_ADMIN]),
        'created_at' => $createdAt,
        'updated_at' => $createdAt
    ];
});