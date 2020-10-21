<?php

use App\PropertyFeature;
use App\Property;
use Faker\Generator as Faker;

$factory->define(PropertyFeature::class, function (Faker $faker) {
    return [
        'property_type' => $faker->randomElement(['ATTCH', 'DETCH', 'DUPSS', 'DUPUD', 'HDUP', '3PLEX', '4PLEX', 'CARRI', 'DETCO', 'APTLO', 'APTHI', 'STACK', 'TOWNH', 'SWIDE', 'DWIDE', 'TSHAR', 'STALL', 'VLOT']),
        'material' => $faker->word,
        'completion_date' => $faker->dateTimeBetween('now', '+2 years'),
        'size' => $faker->numberBetween(100, 1000),
        'rooms_amount' => $faker->numberBetween(1, 10),
        'baths_amount' => $faker->numberBetween(1, 10),
        'bedrooms_amount' => $faker->numberBetween(1, 10),
        'floors' => $faker->numberBetween(1, 3),
        'price' => $faker->numberBetween(200000, 2000000000),
        'property_id' => $faker->unique()->numberBetween(1, Property::count())
    ];
});
