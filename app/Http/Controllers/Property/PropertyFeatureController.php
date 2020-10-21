<?php

namespace App\Http\Controllers\Property;

use App\Http\Requests\PropertyFeatureStoreRequest;
use App\Http\Requests\PropertyFeatureUpdateRequest;
use App\PropertyFeature;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Property as PropertyResource;
use App\Property;
use App\Http\Resources\PropertyFeature as PropertyFeatureResource;

class PropertyFeatureController extends ApiController
{
    /**
     * GET /api/properties/{property}/features
     * Get Features for the Property
     *
     * @param Property $property
     * @return PropertyFeatureResource
     */
    public function index(Property $property)
    {
        $feature = $property->feature;

        $this->checkIfFeatureExists($feature);

        return new PropertyFeatureResource($feature);
    }

    /**
     * POST /api/properties/{property}/features
     * Store new Feature for the Property
     *
     * @param PropertyFeatureStoreRequest $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PropertyFeatureStoreRequest $request, Property $property)
    {
        if (!empty($property->feature)) {
            return $this->errorResponse(trans('response.the_specified_item_has_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyFeature::class)]), 422);
        }

        $property->feature()->create([
            'property_type' => $request->property_type,
            'material' => $request->material,
            'completion_date' => $request->completion_date,
            'size' => $request->size,
            'rooms_amount' => $request->rooms_amount,
            'baths_amount' => $request->baths_amount,
            'bedrooms_amount' => $request->bedrooms_amount,
            'floors' => $request->floors,
            'price' => $request->price,
        ]);

        $property = Property::find($property->id);

        return (new PropertyResource($property))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/properties/{property}/features/{feature}
     * Update the Feature for the Property
     *
     * @param PropertyFeatureUpdateRequest $request
     * @param Property $property
     * @param PropertyFeature $feature
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PropertyFeatureUpdateRequest $request, Property $property, PropertyFeature $feature)
    {
        $this->checkIfFeatureExists($feature);
        $this->checkIfFeatureBelongsToProperty($property, $feature);

        $feature->fill($request->only([
            'property_type',
            'material',
            'completion_date',
            'size',
            'rooms_amount',
            'baths_amount',
            'bedrooms_amount',
            'floors',
            'price',
        ]));

        if ($feature->isClean()) {
            return $this->errorResponse(trans('response.you_need_to_specify_a_different_value_to_update'), 422);
        }

        $feature->save();

        return (new PropertyFeatureResource($feature))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/properties/{property}/features/{feature}
     * Destroy the Feature for the Property
     *
     * @param Property $property
     * @param PropertyFeature $feature
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Property $property, PropertyFeature $feature)
    {
        $this->checkIfFeatureExists($feature);
        $this->checkIfFeatureBelongsToProperty($property, $feature);

        $feature = $property->feature;
        $feature->delete();

        return (new PropertyFeatureResource($feature))->response()->setStatusCode(200);
    }
}
