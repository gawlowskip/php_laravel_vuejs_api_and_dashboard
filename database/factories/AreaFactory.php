<?php

use App\Area;
use Faker\Generator as Faker;

$areaCounter = 0;

$factory->define(Area::class, function (Faker $faker) use (&$areaCounter) {
    $area = [
        'name' => Area::AREAS[$areaCounter]['name'],
        'type' => Area::AREAS[$areaCounter]['type'],
        'country_code' => Area::AREAS[$areaCounter]['country_code'], /* ISO 3166-2 */
        'latitude' => Area::AREAS[$areaCounter]['latitude'],
        'longitude' => Area::AREAS[$areaCounter]['longitude']
    ];
    $areaCounter++;

    return $area;
});
