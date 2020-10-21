<?php

use App\Property;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Property::class, function (Faker $faker) {
    $now = Carbon::now();
    $createdAt = $now->subDays(rand(0, 30))->toDateTimeString();
    return [
        'description' => $faker->paragraph(1),
        'editing_hash' => $faker->md5,
        'bricklayer' => $faker->name,
        'carpenter' => $faker->name,
        'electrician' => $faker->name,
        'vvs' => $faker->name,
        'entrepreneur' => $faker->name,
        'developer_id' => User::all()->random()->id,
        'created_at' => $createdAt
    ];
});
