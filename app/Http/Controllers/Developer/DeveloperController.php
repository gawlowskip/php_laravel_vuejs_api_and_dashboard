<?php

namespace App\Http\Controllers\Developer;

use App\Developer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DeveloperStoreRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Developer as DeveloperResource;

class DeveloperController extends ApiController
{
    /**
     * GET /api/developers
     * Get all Developers
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $developers = Developer::all();

        return DeveloperResource::collection($developers);
    }

    /**
     * GET /api/developers/{developer}
     * Show Developer data
     *
     * @param $developer
     * @return DeveloperResource
     */
    public function show(Developer $developer)
    {
        return new DeveloperResource($developer);
    }

    /**
     * POST /api/developers
     * Store new Developer
     *
     * @param DeveloperStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(DeveloperStoreRequest $request)
    {
        $developer = Developer::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'architect_name' => $request->architect_name,
            'password' => bcrypt($request->password),
            'facebook_id' => $request->facebook_id,
            'street_1' => $request->street_1,
            'street_2' => $request->street_2,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'cvr_number' => $request->cvr_number,
            'verified' => User::VERIFIED_USER
        ]);

        return (new DeveloperResource($developer))->response()->setStatusCode(201);
    }

    /**
     * PUT /api/developers/{developer}
     * Update Developer data
     *
     * @param Request $request
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Developer $developer)
    {
        $rules = [
            'name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'sometimes|email|unique:users,email,' . $developer->id,
            'phone' => 'sometimes',
            'street_1' => 'sometimes',
            'street_2' => 'sometimes',
            'city' => 'sometimes',
            'postal_code' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
            'cvr_number' => 'sometimes',
            'active' => 'sometimes',
        ];
        $this->validate($request, $rules);

        $developer->fill($request->only([
            'name',
            'last_name',
            'email',
            'phone',
            'street_1',
            'street_2',
            'city',
            'postal_code',
            'latitude',
            'longitude',
            'cvr_number',
            'active'
        ]));

        if ($developer->isClean()) {
            return $this->errorResponse(trans('response.you_need_to_specify_a_different_value_to_update'), 422);
        }

        $developer->save();

        return (new DeveloperResource($developer))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/developers/{developer}
     * Delete Developer
     *
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Developer $developer)
    {
        $developer->delete();

        return (new DeveloperResource($developer))->response()->setStatusCode(200);
    }
}
