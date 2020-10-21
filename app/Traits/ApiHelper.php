<?php

namespace App\Traits;

use App\Area;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Stripe\Plan;
use Stripe\Stripe;
use Exception;

trait ApiHelper
{
    /**
     * Find Areas by Latitude and Longitude
     *
     * @param Request $request
     * @return array|Collection
     */
    protected function findAreasByLatitudeAndLongitude(Request $request)
    {
        if (!$request->has('latitude') || !$request->has('longitude')) {
            return [];
        }

        $kilometers = null;
        if ($request->has('kilometers')) {
            $kilometers = $request->kilometers;
        }

        $rules = [
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'kilometers' => 'sometimes|numeric',
        ];

        $this->validate($request, $rules);

        /* Filter ads by location (latitude and longitude) */
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $areas = Area::all();
        $distanceTo = collect();

        foreach ($areas as $area) {
            $distanceTo[$area->id] = collect();
            if (count($area->latitude) == count($area->longitude)) {
                foreach ($area->latitude as $key => $lat) {
                    $lng = $area->longitude[$key];
                    $distanceTo[$area->id]->push($this->calculateDistanceBetweenTwoCoordinates($latitude, $longitude, $lat, $lng, 'km'));
                }
            }
            $distanceTo[$area->id] = ($distanceTo[$area->id])->min();
        }

        $areaIds = collect();

        if ($kilometers) {
            foreach ($distanceTo as $areaId => $areaDistance) {
                if ($kilometers && $areaDistance <= $kilometers) {
                    $areaIds->push($areaId);
                }
            }
        } else {
            $minDistanceTo = $distanceTo->min();
            $areaIds->push($distanceTo->search($minDistanceTo));
        }

        // $areas = Area::whereIn('id', $areaIds)->get();

        return $areaIds;
    }

    /**
     * Calculate distance between two coordinates
     *
     * @param $latitude1
     * @param $longitude1
     * @param $latitude2
     * @param $longitude2
     * @param $unit
     * @return float|int
     */
    protected function calculateDistanceBetweenTwoCoordinates($latitude1, $longitude1, $latitude2, $longitude2, $unit)
    {
        if (($latitude1 == $latitude2) && ($longitude1 == $longitude2)) {
            return 0;
        } else {
            $theta = $longitude1 - $longitude2;
            $dist = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;

            $unit = strtoupper($unit);

            if ($unit == "km") {
                /* Kilometers */
                return ($miles * 1.609344);
            } else if ($unit == "n") {
                /* Nautical miles */
                return ($miles * 0.8684);
            } else {
                /* Miles */
                return $miles;
            }
        }
    }

    /**
     * Store model visits
     *
     * @param Request $request
     * @param $item
     */
    protected function storeModelVisits(Request $request, $item)
    {
        $userId = $request->has('user_id') ? $request->user_id : null;

        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
            $userId = $user->id;
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $visit = new Visit();

        /* JWT auth user */
        /*
        $user = $request->user();
        $visit->user_id = !empty($user) ? $user->id : null;
        */

        $visit->user_id = $userId;
        $visit->ip_address = $ipAddress;
        $item->visits()->save($visit);
    }

    /**
     * Get all Stripe Plans
     *
     * @return Collection
     */
    protected function getAllStripePlans()
    {
        try {
            $stripeKey = env('STRIPE_KEY');
            $stripeSecret = env('STRIPE_SECRET');

            $this->checkIfStripeKeysExists($stripeKey, $stripeSecret);

            Stripe::setApiKey($stripeSecret);
            $stripePlans = Plan::all();
        } catch (Exception $e) {
            $stripePlans = [];
        }

        $plans = collect();

        foreach ($stripePlans as $plan) {
            $plans->push($plan);
        }

        return $plans;
    }

    /**
     * Check if file is encoded in Base64
     *
     * @param $file
     * @return bool
     */
    protected function fileIsEncodedInBase64($file)
    {
        return base64_encode(base64_decode($file)) === $file;
    }
}