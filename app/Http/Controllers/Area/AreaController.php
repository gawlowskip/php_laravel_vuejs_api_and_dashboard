<?php

namespace App\Http\Controllers\Area;

use App\Area;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Ad as AdResource;
use App\Http\Resources\Area as AreaResource;
use App\User;

class AreaController extends ApiController
{
    /**
     * GET /api/areas
     * Get the Areas
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $areas = Area::all();

        return AreaResource::collection($areas);
    }

    /**
     * GET /api/areas/{area}
     * Get the Area data
     *
     * @param Area $area
     * @return AreaResource
     */
    public function show(Area $area)
    {
        return new AreaResource($area);
    }

    /**
     * GET /api/areas/{area}/ads
     * Get the Ads for the Area
     *
     * @param Area $area
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAreaAds(Area $area)
    {
        $ads = $area->ads()->whereHas('developer', function ($query) {
            $query->where('active', User::ACTIVE);
        })->get();

        return AdResource::collection($ads);
    }

    /**
     * GET /api/areas/{area}/ad
     * Get the random Ad for the Area
     *
     * @param Area $area
     * @return AdResource
     */
    public function getRandomAdForArea(Area $area)
    {
        $ads = $area->ads()->whereHas('developer', function ($query) {
            $query->where('active', User::ACTIVE);
        })->get();
        $ad = $ads->random();

        return new AdResource($ad);
    }
}
