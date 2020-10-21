<?php

use App\Ad;
use App\Developer;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Ad::class, function (Faker $faker) {
    $fromDate = Carbon::createFromTimestamp(strtotime('+' . rand(1, 9) . ' days'))->toDateString();
    $toDate = Carbon::createFromTimestamp(strtotime('+' . rand(16, 30) . ' days'))->toDateString();
    $image = $faker->randomElement([null, '013.jpg', '014.jpg', '015.jpg', '016.jpg', '017.jpg', '018.jpg', '019.jpg', '020.jpg', '021.jpg', '022.jpg', '023.jpg', '024.jpg']);
    $external_image_url = null;
    if ($image == null) {
        $external_image_url = $faker->imageUrl;
    }

    return [
        'developer_id' => $faker->numberBetween(1, Developer::count()),
        'from_date' => $fromDate,
        'to_date' => $toDate,
        'active' => $faker->boolean,
        'image' => $image,
        'external_image_url' => $external_image_url,
        'url' => $faker->url,
        'price' => $faker->randomFloat(2, 100, 999),
        'price_lead' => $faker->randomFloat(2, 0, 99),
        'seconds' => $faker->numberBetween(0, 60),
    ];
});
