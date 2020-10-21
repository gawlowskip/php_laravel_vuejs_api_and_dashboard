<?php

use App\Property;
use App\PropertyVideo;
use Faker\Generator as Faker;

$factory->define(PropertyVideo::class, function (Faker $faker) {
    return [
        'filename' => $faker->randomElement(['001.mp4', '002.mp4', '003.mp4', '004.mp4', '005.mp4', '006.mp4', '007.mp4', '008.mp4', '009.mp4', '010.mp4', '011.mp4', '012.mp4']),
        'thumbnail' => $faker->randomElement(['001_thumbnail.jpg', '002_thumbnail.jpg', '003_thumbnail.jpg', '004_thumbnail.jpg', '005_thumbnail.jpg', '006_thumbnail.jpg', '007_thumbnail.jpg', '008_thumbnail.jpg', '009_thumbnail.jpg', '010_thumbnail.jpg', '011_thumbnail.jpg', '012_thumbnail.jpg']),
        'property_id' => $faker->numberBetween(1, Property::count()),
    ];
});
