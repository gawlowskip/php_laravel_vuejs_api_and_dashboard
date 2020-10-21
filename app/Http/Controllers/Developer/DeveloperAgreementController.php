<?php

namespace App\Http\Controllers\Developer;

use App\Agreement;
use App\Developer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DeveloperAgreementDestroyWithPaymentRequest;
use App\Http\Requests\DeveloperAgreementStoreWithPaymentRequest;
use App\Http\Requests\DeveloperAgreementUpdateRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Agreement as AgreementResource;
use Stripe\Plan;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

class DeveloperAgreementController extends ApiController
{
    /**
     * GET /api/developers/{developer}/agreements
     * Get all Agreements for the Developer
     *
     * @param Request $request
     * @param Developer $developer
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Developer $developer)
    {
        $agreements = $developer->agreements()->withTrashed()->get();

        return AgreementResource::collection($agreements);
    }

    /**
     * POST /api/stripe/subscriptions
     * Store new Agreement for the Developer with payment
     *
     * @param DeveloperAgreementStoreWithPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeWithPayment(DeveloperAgreementStoreWithPaymentRequest $request)
    {
        /* Similar to ApiDatabase@factoryAgreement */
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');

        $this->checkIfStripeKeysExists($stripeKey, $stripeSecret);

        Stripe::setApiKey($stripeSecret);

        $stripeToken = $request->token;
        // $stripeToken = 'tok_visa';
        $stripePlanId = $request->stripe_plan_id;
        $userId = $request->user_id;
        $user = User::findOrFail($userId);

        $this->checkIfUserActive($user);

        try {
            $plan = Plan::retrieve($stripePlanId);

            if (empty($plan)) {
                return $this->errorResponse(trans('response.the_specified_stripe_plan_does_not_exists', ['stripe_plan_id' => $stripePlanId]), 404);
            }

            $activeAgreement = $user->activeAgreements()->first();

            if (!empty($activeAgreement)) {
                throw new HttpException(422, 'Failed: ' . trans('response.the_user_has_an_active_agreement_until', ['type' => $activeAgreement->type, 'to' => $activeAgreement->to_date, 'id' => $activeAgreement->id]));
            }

            $agreementType = $request->has('type') ? $request->type : Agreement::TYPE_REGULAR;

            $from = null;
            $to = null;
            $trialStartsAt = null;
            $trialEndsAt = null;
            $price = 0;

            switch ($agreementType) {
                case Agreement::TYPE_REGULAR:
                    $from = Carbon::now()->toDateTimeString();
                    $to = Carbon::createFromTimestamp(strtotime("{$from} +{$plan->interval_count} {$plan->interval}s"))->toDateTimeString();
                    $price = (float)($plan->amount / 100);
                    break;
                case Agreement::TYPE_TRIAL:
                    $trialStartsAt = $request->has('trial_starts_at') ? $request->get('trial_starts_at') : null;
                    $trialEndsAt = $request->has('trial_ends_at') ? $request->get('trial_ends_at') : null;
                    break;
            }

            $agreement = Agreement::create([
                'developer_id' => $user->id,
                'from' => $from,
                'to' => $to,
                'type' => $agreementType,
                'trial_starts_at' => $trialStartsAt,
                'trial_ends_at' => $trialEndsAt,
                'stripe_plan' => $plan->id,
                'price' => $price,
                'currency' => $plan->currency,
                'verified' => !Agreement::VERIFIED_AGREEMENT,
                'status' => null
            ]);

            $charge = $user->charge($plan->amount, [
                'description' => trans('response.charge_for_plan', ['plan_id' => $plan->id]),
                'source' => $stripeToken
            ]);

            $agreement->stripe_charge = $charge->id;
            $agreement->verified = Agreement::VERIFIED_AGREEMENT;
            $agreement->status = $charge->status;
            $agreement->save();

            return (new AgreementResource($agreement))->response()->setStatusCode(201);
        } catch (Exception $e) {
            if (isset($agreement)) {
                $agreement->verified = !Agreement::VERIFIED_AGREEMENT;
                $agreement->status = $e->getMessage();
                $agreement->save();
            }

            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * GET /api/developers/{developer}/agreements/{agreement}
     * Get the Agreement data for Developer
     *
     * @param Developer $developer
     * @param $agreement
     * @return AgreementResource
     */
    public function show(Developer $developer, $agreement)
    {
        $agreement = $developer->agreements()->withTrashed()->where('id', $agreement)->firstOrFail();

        return new AgreementResource($agreement);
    }

    /**
     * PUT /api/developers/{developer}/agreements/{agreement}
     * Update Agreement data for the Developer
     *
     * @param DeveloperAgreementUpdateRequest $request
     * @param Developer $developer
     * @param $agreement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DeveloperAgreementUpdateRequest $request, Developer $developer, $agreement)
    {
        $agreement = $developer->agreements()->withTrashed()->where('id', $agreement)->firstOrFail();

        $agreement->fill($request->only([
            'type',
        ]));

        $stripePlanId = $request->stripe_plan;
        $this->plans = $this->getAllStripePlans();
        $plan = $this->plans->filter(function ($value, $key) use ($stripePlanId) {
            return $value->id == $stripePlanId;
        })->first();

        if (empty($plan)) {
            return $this->errorResponse(trans('response.the_specified_stripe_plan_does_not_exists', ['stripe_plan_id' => $stripePlanId]), 404);
        }

        $agreement->stripe_plan = $request->stripe_plan;

        switch ($request->type) {
            case Agreement::TYPE_REGULAR:
                $agreement->from = Carbon::now()->toDateTimeString();
                $agreement->to = Carbon::createFromTimestamp(strtotime("{$agreement->from} +{$plan->interval_count} {$plan->interval}s"))->toDateTimeString();
                $agreement->trial_starts_at = null;
                $agreement->trial_ends_at = null;
                $agreement->price = (float)($plan->amount / 100);
                $agreement->verified = Agreement::VERIFIED_AGREEMENT;
                break;
            case Agreement::TYPE_TRIAL:
                $agreement->from = null;
                $agreement->to = null;
                $agreement->trial_starts_at = $request->has('trial_starts_at') ? $request->get('trial_starts_at') : null;
                $agreement->trial_ends_at = $request->has('trial_ends_at') ? $request->get('trial_ends_at') : null;
                $agreement->verified = Agreement::VERIFIED_AGREEMENT;
                break;
        }

        if ($agreement->deleted_at) {
            $agreement->deleted_at = null;
        }

        $agreement->save();

        return (new AgreementResource($agreement))->response()->setStatusCode(200);
    }

    /**
     * DELETE /api/developers/{developer}/agreements/{agreement}
     * Destroy the Agreement for the Developer
     *
     * @param Developer $developer
     * @param $agreement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Developer $developer, $agreement)
    {
        $agreement = $developer->agreements()->withTrashed()->where('id', $agreement)->firstOrFail();
        $agreement->forceDelete();

        return (new AgreementResource($agreement))->response()->setStatusCode(200);
    }

    /**
     * POST /api/stripe/subscriptions/cancel
     * Cancel active agreement assigned to Developer
     *
     * @param DeveloperAgreementDestroyWithPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroyWithPayment(DeveloperAgreementDestroyWithPaymentRequest $request)
    {
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');

        $this->checkIfStripeKeysExists($stripeKey, $stripeSecret);

        Stripe::setApiKey($stripeSecret);

        $stripeToken = $request->token;
        // $stripeToken = 'tok_visa';
        $stripePlanId = $request->stripe_plan_id;
        $developerId = $request->developer_id;
        $developer = Developer::findOrFail($developerId);

        $now = Carbon::now();
        $agreement = Agreement::withTrashed()->where('stripe_plan', $stripePlanId)->where('developer_id', $developer->id)->where('agreements.to', '>=', $now)->where('verified', 1)->first();

        if (!count($agreement)) {
            $otherAgreement = Agreement::withTrashed()->where('developer_id', $developer->id)->where('agreements.to', '>=', $now)->where('verified', 1)->first();
            if (count($otherAgreement)) {
                return $this->errorResponse(trans('response.the_user_has_an_active_agreement_but_uses_a_different_plan', ['plan_id' => $otherAgreement->stripe_plan]), 422);
            }
            return $this->errorResponse(trans('response.the_user_has_not_an_active_agreement'), 422);
        }

        if ($agreement->deleted_at) {
            return $this->errorResponse(trans('response.the_agreement_has_already_been_canceled', ['to' => $agreement->to]), 422);
        }

        $agreement->delete();
    }

    /**
     * GET /api/stripe/plans
     * Get Stripe plans
     *
     * @return \Illuminate\Http\JsonResponse|\Stripe\Collection
     */
    public function getStripePlans()
    {
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');

        $this->checkIfStripeKeysExists($stripeKey, $stripeSecret);

        Stripe::setApiKey($stripeSecret);

        try {
            $plans = Plan::all();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return response()->json($plans);
    }
}
