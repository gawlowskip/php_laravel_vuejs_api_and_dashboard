<?php

use App\Agreement;
use App\Developer;
use Carbon\Carbon;
use Faker\Generator as Faker;

$developerCounter = 0;
$developers = [];
$factory->define(Agreement::class, function (Faker $faker) use (&$developerCounter, &$developers) {
    if (empty($developers)) {
        $developers = Developer::all()->pluck('id')->toArray();
    }
    $from = Carbon::createFromTimestamp(strtotime('-' . rand(1, 9) . ' days'))->toDateTimeString();
    $to = Carbon::createFromTimestamp(strtotime('+' . rand(16, 30) . ' days'))->toDateTimeString();
    $agreement = [
        'developer_id' => $developers[$developerCounter],
        'from' => $from,
        'to' => $to,
        'type' => Agreement::TYPE_REGULAR, //TODO: Modify Agreement factory to generate trial agreements as well
        'stripe_plan' => null,
        'stripe_charge' => null,
        'price' => $faker->randomFloat(2, 100, 2000),
        'currency' => 'dkk',
        'verified' => $faker->numberBetween(0, 1),
    ];
    $developerCounter++;
    return $agreement;
});
