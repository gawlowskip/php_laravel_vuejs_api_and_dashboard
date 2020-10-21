<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PropertyVideosStoreRequest;
use App\PropertyVideo;
use App\Property;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PropertyVideo as PropertyVideoResource;

class PropertyVideoController extends ApiController
{
    /**
     * GET /api/properties/{property}/videos
     * Get the Videos for the Property
     *
     * @param Property $property
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Property $property)
    {
        $videos = $property->videos;

        return PropertyVideoResource::collection($videos);
    }

    /**
     * POST /api/properties/{property}/videos
     * Store new Videos for the Property
     *
     * @param PropertyVideosStoreRequest $request
     * @param Property $property
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PropertyVideosStoreRequest $request, Property $property)
    {
        $videos = collect();

        foreach ($request->videos as $key => $video) {
            $video_extension = '.mp4';
            $thumbnail_extension = '.jpg';
            if ($this->fileIsEncodedInBase64($video)) {
                $filename = Storage::disk('videos')->put('', base64_decode($video));

                $thumbnail_path = url("videos");
                $video_path = url("videos/{$filename}{$video_extension}");
                $thumbnail_image = $filename . '_thumbnail' . $thumbnail_extension;
            } else {
                $filename = Storage::disk('videos')->put('', $video);
            }

            $videoData = [
                'filename' => $filename
            ];

            $video = $property->videos()->create($videoData);
            $videos->push($video);
        }

        return PropertyVideoResource::collection($videos);
    }

    /**
     * DELETE /api/properties/{property}/videos/{video}
     * Destroy the Video for the Property
     *
     * @param Property $property
     * @param PropertyVideo $video
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Property $property, PropertyVideo $video)
    {
        $this->checkIfVideoExists($video);
        $this->checkIfVideoBelongsToProperty($property, $video);

        $video->delete();
        $propertyVideo = $property->videos->where('id', $video->id)->first();
        Storage::disk('videos')->delete($propertyVideo->getOriginal('filename'));

        return (new PropertyVideoResource($video))->response()->setStatusCode(200);
    }
}