<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Ad as AdResource;
use App\Http\Resources\User as UserResource;
use App\Lead;
use App\Http\Resources\Lead as LeadResource;

class LeadController extends ApiController
{
    /**
     * GET /api/leads
     * Get all Leads
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $leads = Lead::all();

        return LeadResource::collection($leads);
    }

    /**
     * GET /api/leads/{lead}
     * Get the Lead data
     *
     * @param Lead $lead
     * @return LeadResource
     */
    public function show(Lead $lead)
    {
        return new LeadResource($lead);
    }

    /**
     * GET /api/leads/{lead}/ad
     * Get the Ad data for the Lead
     *
     * @param Lead $lead
     * @return AdResource|\Illuminate\Http\JsonResponse
     */
    public function getAdForLead(Lead $lead)
    {
        $ad = $lead->ad;

        return new AdResource($ad);
    }

    /**
     * GET /api/leads/{lead}/user
     * Get the User data for the Lead
     *
     * @param Lead $lead
     * @return UserResource
     */
    public function getUserForLead(Lead $lead)
    {
        return new UserResource($lead->user);
    }
}
