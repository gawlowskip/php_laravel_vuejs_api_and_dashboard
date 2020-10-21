<?php

use App\Property;
use App\PropertyLocation;
use Faker\Generator as Faker;

$factory->define(PropertyLocation::class, function (Faker $faker) {
    return [
        'district' => $faker->state,
        'city' => $faker->city,
        'street' => $faker->streetName,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'property_id' => $faker->unique()->numberBetween(1, Property::count()),
    ];
});
