<?php

use App\PropertyImage;
use App\Property;
use Faker\Generator as Faker;

$factory->define(PropertyImage::class, function (Faker $faker) {
    return [
        'filename' => $faker->randomElement(['001.jpg', '002.jpg', '003.jpg', '004.jpg', '005.jpg', '006.jpg', '007.jpg', '008.jpg', '009.jpg', '010.jpg', '011.jpg', '012.jpg']),
        'property_id' => $faker->numberBetween(1, Property::count()),
    ];
});