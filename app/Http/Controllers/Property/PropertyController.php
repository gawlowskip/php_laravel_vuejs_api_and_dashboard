<?php

namespace App\Http\Controllers\Property;

use App\Http\Requests\PropertyUpdateRequest;
use App\Http\Resources\Developer as DeveloperResource;
use App\Http\Resources\Property as PropertyResource;
use App\Property;
use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PropertyController extends ApiController
{
    /**
     * GET /api/properties
     * Get all Properties (sortable, filterable, pagination)
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        /* Pattern - paginate data */
//        $properties = Property::where('id', '!=', null);
//        $properties = $this->sortPaginatedData($properties);
//        $properties = $this->filterPaginatedData($properties);
//        $properties = $properties->paginate(5);

        /* Pattern - collection */
        $properties = Property::all();
        $properties = $this->sortData($properties);
        $properties = $this->filterData($properties);

        return PropertyResource::collection($properties);
    }

    /**
     * POST /api/properties
     * Store new Property
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        /* Main validation */
        $rules = [
            'description' => 'sometimes',
            'editing_hash' => 'sometimes',
            'bricklayer' => 'sometimes',
            'carpenter' => 'sometimes',
            'electrician' => 'sometimes',
            'vvs' => 'sometimes',
            'entrepreneur' => 'sometimes',
            'developer' => 'required|array',
            'developer.id' => 'sometimes',
            'developer.name' => 'sometimes',
            'developer.last_name' => 'sometimes',
            'developer.email' => 'sometimes',
            'developer.phone' => 'sometimes',
            'developer.architect_name' => 'sometimes',
            'developer.password' => 'sometimes',
            'developer.facebook_id' => 'sometimes',
            'developer.street_1' => 'sometimes',
            'developer.street_2' => 'sometimes',
            'developer.city' => 'sometimes',
            'developer.postal_code' => 'sometimes',
            'developer.latitude' => 'sometimes',
            'developer.longitude' => 'sometimes',
            'developer.cvr_number' => 'sometimes',
            'developer.active' => 'sometimes',
            'user_id' => 'sometimes'
        ];

        $this->validate($request, $rules);
        $data = $request->all();

        /* Features validation */
        if ($request->has('features')) {
            $featuresRules = [
                'features.property_type' => 'sometimes',
                'features.material' => 'sometimes',
                'features.completion_date' => 'sometimes',
                'features.size' => 'sometimes',
                'features.rooms_amount' => 'sometimes',
                'features.baths_amount' => 'sometimes',
                'features.bedrooms_amount' => 'sometimes',
                'features.floors' => 'sometimes',
                'features.price' => 'sometimes'
            ];
            $this->validate($request, $featuresRules);
        }

        /* Location validation */
        if ($request->has('location')) {
            $locationRules = [
                'location.district' => 'sometimes',
                'location.city' => 'sometimes',
                'location.street' => 'sometimes',
                'location.latitude' => 'sometimes',
                'location.longitude' => 'sometimes',
            ];
            $this->validate($request, $locationRules);
        }

        /* Images validation */
        if ($request->has('images')) {
            $imagesRules = [
                'images' => 'sometimes',
            ];
            $this->validate($request, $imagesRules);
        }

        /* Videos validation */
        if ($request->has('videos')) {
            $videoRules = [
                'videos' => 'sometimes'
            ];
            $this->validate($request, $videoRules);
        }

        /* Recognize if user is logged by id, email or by facebook_id */
        if (!$request->has('user_id') || $request->user_id == '') {
            if (isset($data['developer']['facebook_id']) && $data['developer']['facebook_id'] != '') {
                $data['user_id'] = $data['developer']['facebook_id'];
            } elseif (isset($data['developer']['email']) && $data['developer']['email'] != '') {
                $data['user_id'] = $data['developer']['email'];
            } elseif (isset($data['developer']['id']) && $data['developer']['id'] != '') {
                $data['user_id'] = $data['developer']['id'];
            }
        }

        try {
            DB::beginTransaction();

            $user = $data['developer'];
            $user['password'] = bcrypt($request->password);
            $user['verified'] = User::UNVERIFIED_USER;
            unset($data['developer']);

            $developer = [];

            if (isset($data['user_id'])) {
                $developer = User::find($data['user_id']);
            }

            try {
                if (empty($developer)) {
                    $developer = User::create($user);
                }
            } catch (Exception $e) {
                if (empty($developer)) {
                    throw new HttpException(404, trans('response.does_not_exists_any_model_with_the_specified_identificator', ['model' => 'User']));
                }
            }

            $this->checkIfUserActive($developer);

            $data['developer_id'] = $developer->id;

            if (!empty($request->features)) {
                $features = $request->features;
                unset($data['features']);
            }

            if (!empty($request->location)) {
                $location = $request->location;
                unset($data['location']);
            }

            if (!empty($request->images)) {
                $images = $request->images;
                unset($data['images']);
            }

            if (!empty($request->videos)) {
                $videos = $request->videos;
                unset($data['videos']);
            }

            $property = Property::create($data);

            if (isset($features)) {
                $property->feature()->create([
                    'property_type' => $features['property_type'],
                    'material' => $features['material'],
                    'completion_date' => $features['completion_date'],
                    'size' => $features['size'],
                    'rooms_amount' => $features['rooms_amount'],
                    'baths_amount' => $features['baths_amount'],
                    'bedrooms_amount' => $features['bedrooms_amount'],
                    'floors' => $features['floors'],
                    'price' => $features['price']
                ]);
            }

            if (isset($location)) {
                $property->location()->create([
                    'district' => $location['district'],
                    'city' => $location['city'],
                    'street' => $location['street'],
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                ]);
            }

            if (isset($images)) {
                foreach ($images as $key => $image) {
                    if ($this->fileIsEncodedInBase64($image)) {
                        $image = str_replace('data:image/png;base64,', '', $image);
                        $image = str_replace(' ', '+', $image);
                        $filename = $property->id . '_' . strtotime('now') . '_' . rand(100000, 999999) . '.png';
                        Storage::disk('images')->put($filename, base64_decode($image));

                        $property->images()->create([
                            'filename' => $filename
                        ]);
                    } else {
                        $filename = Storage::disk('images')->put('', $image);
                        $property->images()->create([
                            'filename' => $filename
                        ]);
                    }
                }
            }

            if (isset($videos)) {
                foreach ($videos as $key => $video) {
                    $filename = $property->id . '_' . strtotime('now') . '_' . rand(100000, 999999) . '.mp4';
                    Storage::disk('videos')->put($filename, base64_decode($video));

                    $property->videos()->create([
                        'filename' => $filename
                    ]);
                }
            }

            DB::commit();

            return (new PropertyResource($property))->response()->setStatusCode(201);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            \Log::error($e->getCode());
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * GET /api/properties/{property}
     * Get the Property data
     *
     * @param Property $property
     * @return PropertyResource
     */
    public function show(Property $property)
    {
        return new PropertyResource($property);
    }

    /**
     * PUT /api/properties/{property}
     * Update the Property data
     *
     * @param PropertyUpdateRequest $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PropertyUpdateRequest $request, Property $property)
    {
        $property->fill($request->only([
            'description',
            'editing_hash',
            'bricklayer',
            'carpenter',
            'electrician',
            'vvs',
            'entrepreneur'
        ]));

        if ($property->isClean()) {
            return $this->errorResponse(trans('response.you_need_to_specify_a_different_value_to_update'), 422);
        }

        $property->save();

        return (new PropertyResource($property))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/properties/{property}
     * Destroy the Property
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(Property $property)
    {
        $property->delete();

        if (!empty($property->feature)) {
            $feature = $property->feature;
            $feature->delete();
        }

        if (!empty($property->location)) {
            $location = $property->location;
            $location->delete();
        }

        if (!empty($property->images)) {
            foreach ($property->images as $image) {
                $image->delete();
                Storage::disk('images')->delete($image->getOriginal('filename'));
            }
        }

        if (!empty($property->videos)) {
            foreach ($property->videos as $video) {
                $video->delete();
                Storage::disk('videos')->delete($video->getOriginal('filename'));
            }
        }

        return (new PropertyResource($property))->response()->setStatusCode(200);
    }

    /**
     * GET /api/properties/{property}/developers
     * Get the Developer data for the Property
     *
     * @param Property $property
     * @return DeveloperResource
     */
    public function getDeveloperForProperty(Property $property)
    {
        $developer = $property->developer;

        return new DeveloperResource($developer);
    }

    /**
     * POST /api/properties/{property}/visit
     * Store new visit for Property
     *
     * @param Request $request
     * @param Property $property
     */
    public function visit(Request $request, Property $property)
    {
        $this->storeModelVisits($request, $property);
    }
}
