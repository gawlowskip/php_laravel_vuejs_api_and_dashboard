<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PropertyImagesStoreRequest;
use App\PropertyImage;
use App\Property;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PropertyImage as PropertyImageResource;

class PropertyImageController extends ApiController
{
    /**
     * GET /api/properties/{property}/images
     * Get the Images for the Property
     *
     * @param Property $property
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Property $property)
    {
        $images = $property->images;

        return PropertyImageResource::collection($images);
    }

    /**
     * POST /api/properties/{property}/images
     * Store new Images for the Property
     *
     * @param PropertyImagesStoreRequest $request
     * @param Property $property
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PropertyImagesStoreRequest $request, Property $property)
    {
        $images = collect();

        foreach ($request->images as $key => $image) {
            if ($this->fileIsEncodedInBase64($image)) {
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $filename = $property->id . '_' . strtotime('now') . '_' . rand(100000, 999999) . '.png';
                Storage::disk('images')->put($filename, base64_decode($image));
                //$filename = $image->store('');
                $img = $property->images()->create([
                    'filename' => $filename
                ]);
                $images->push($img);
            } else {
                $filename = Storage::disk('images')->put('', $image);
                $img = $property->images()->create([
                    'filename' => $filename
                ]);
                $images->push($img);
            }
        }

        return PropertyImageResource::collection($images);
    }

    /**
     * DELETE /api/properties/{property}/images/{image}
     * Destroy the Image for the Property
     *
     * @param Property $property
     * @param PropertyImage $image
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Property $property, PropertyImage $image)
    {
        $this->checkIfImageExists($image);
        $this->checkIfImageBelongsToProperty($property, $image);

        $image->delete();
        $propertyImage = $property->images->where('id', $image->id)->first();
        Storage::disk('images')->delete($propertyImage->getOriginal('filename'));

        return (new PropertyImageResource($image))->response()->setStatusCode(200);
    }
}