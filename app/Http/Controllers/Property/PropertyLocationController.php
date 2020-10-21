<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PropertyLocationStoreRequest;
use App\Http\Requests\PropertyLocationUpdateRequest;
use App\PropertyLocation;
use App\Property;
use App\Http\Resources\PropertyLocation as PropertyLocationResource;

class PropertyLocationController extends ApiController
{
    /**
     * GET /api/properties/{property}/locations
     * Get the Location for the Property
     *
     * @param Property $property
     * @return PropertyLocationResource
     */
    public function index(Property $property)
    {
        $location = $property->location;

        $this->checkIfLocationExists($location);

        return new PropertyLocationResource($location);
    }

    /**
     * POST /api/properties/{property}/locations
     * Store new Location for the Property
     *
     * @param PropertyLocationStoreRequest $request
     * @param Property $property
     * @return PropertyLocationResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PropertyLocationStoreRequest $request, Property $property)
    {
        if(!empty($property->location)) {
            return $this->errorResponse(trans('response.the_specified_item_has_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyLocation::class)]), 422);
        }

        $location = $property->location()->create([
            'district' => $request->district,
            'city' => $request->city,
            'street' => $request->street,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return (new PropertyLocationResource($location))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/properties/{property}/locations/{location}
     * Update the Location for the Property
     *
     * @param PropertyLocationUpdateRequest $request
     * @param Property $property
     * @param PropertyLocation $location
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PropertyLocationUpdateRequest $request, Property $property, PropertyLocation $location)
    {
        $this->checkIfLocationExists($location);
        $this->checkIfLocationBelongsToProperty($property, $location);

        $location->fill($request->only([
            'district',
            'city',
            'street',
            'latitude',
            'longitude',
        ]));

        if ($location->isClean()) {
            return $this->errorResponse(trans('response.you_need_to_specify_a_different_value_to_update'), 422);
        }

        $location->save();

        return (new PropertyLocationResource($location))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/properties/{property}/locations/{location}
     * Destroy the Location for the Property
     *
     * @param Property $property
     * @param PropertyLocation $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Property $property, PropertyLocation $location)
    {
        $this->checkIfLocationExists($location);
        $this->checkIfLocationBelongsToProperty($property, $location);

        $location = $property->location;
        $location->delete();

        return (new PropertyLocationResource($location))->response()->setStatusCode(200);
    }
}
