<?php

namespace App\Http\Controllers\User;

use App\Ad;
use App\Http\Controllers\ApiController;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\Lead as LeadResource;
use App\Http\Resources\Property as PropertyResource;
use App\Lead;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Exception;

class UserController extends ApiController
{
    /**
     * GET /api/users
     * Get all Users
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    /**
     * POST /api/users
     * Store new User
     *
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;

        $user = User::create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * GET /api/users/{user}
     * Get User data
     *
     * @param $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * PUT /api/users/{user}
     * Update User data
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'sometimes',
            'last_name' => 'sometimes',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|nullable|min:8',
            'architect_name' => 'sometimes|nullable|min:8',
            'password' => 'min:6',
            'facebook_id' => 'sometimes|nullable|unique:users,facebook_id,' . $user->id,
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

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->email = $request->email;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('architect_name')) {
            $user->architect_name = $request->architect_name;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('facebook_id')) {
            $user->facebook_id = $request->facebook_id;
        }

        if ($request->has('street_1')) {
            $user->street_1 = $request->street_1;
        }

        if ($request->has('street_2')) {
            $user->street_2 = $request->street_2;
        }

        if ($request->has('city')) {
            $user->city = $request->city;
        }

        if ($request->has('postal_code')) {
            $user->postal_code = $request->postal_code;
        }

        if ($request->has('latitude')) {
            $user->latitude = $request->latitude;
        }

        if ($request->has('longitude')) {
            $user->longitude = $request->longitude;
        }

        if ($request->has('cvr_number')) {
            $user->cvr_number = $request->cvr_number;
        }

        if ($request->has('active')) {
            $user->active = $request->active;
        }

        if (!$user->isDirty()) {
            return $this->errorResponse(trans('response.you_need_to_specify_a_different_value_to_update'), 422);
        }

        $user->save();

        return (new UserResource($user))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/users/{user}
     * Destroy User
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return (new UserResource($user))->response()->setStatusCode(200);
    }

    /**
     * GET /api/users/{user}/leads
     * Get the Leads for the User
     *
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLeadsForUser(User $user)
    {
        $leads = $user->leads;

        return LeadResource::collection($leads);
    }

    /**
     * GET /api/users/{user}/properties
     * Get all Property for the User
     *
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getPropertiesForUser(User $user)
    {
        $properties = $user->properties;

        return PropertyResource::collection($properties);
    }

    /**
     * POST /api/users/{user}/ads/{ad}/leads
     * Store new Lead for the Ad
     *
     * @param Request $request
     * @param $user
     * @param Ad $ad
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeLeadForUser(Request $request, User $user, Ad $ad)
    {
        $rules = [
            'full_name' => 'sometimes',
            'email' => 'sometimes',
            'clicked_on' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
        ];
        $this->validate($request, $rules);

        $data = $request->all();
        $data['user_id'] = $user->id;

        if (!$request->has('full_name')) {
            $data['full_name'] = $user->name . ' ' . $user->last_name;
        }

        if (!$request->has('email')) {
            $data['email'] = $user->email;
        }

        if (!$request->has('clicked_on')) {
            $data['clicked_on'] = Carbon::now()->toDateTimeString();
        }

        /* Get latitude and longitude from IP Address */
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $latitude = null;
        $longitude = null;

        try {
            $client = new Client();
            $res = $client->request('GET', "http://ipinfo.io/{$ipAddress}/geo");
            $ipDetails = json_decode($res->getBody());
            $locationDetails = explode(",", $ipDetails->loc);
            $latitude = isset($locationDetails[0]) ? $locationDetails[0] : null;
            $longitude = isset($locationDetails[1]) ? $locationDetails[1] : null;
        } catch (Exception $e) {
            \Log::info($e->getMessage());
        }

        if (!$request->has('latitude') && !$request->has('longitude')) {
            $data['latitude'] = $latitude;
            $data['longitude'] = $longitude;
        }

        $lead = $ad->leads()->create($data);

        return (new LeadResource($lead))->response()->setStatusCode(201);
    }

    /**
     * DELETE /api/users/{user}/ads/{ad}/leads/{lead}
     * Destroy the Lead from the Ad
     *
     * @param User $user
     * @param Ad $ad
     * @param Lead $lead
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroyLeadForUser(User $user, Ad $ad, Lead $lead)
    {
        $this->checkIfUserIsLeadOwner($user, $lead);

        $lead->delete();

        return (new LeadResource($lead))->response()->setStatusCode(200);
    }
}
