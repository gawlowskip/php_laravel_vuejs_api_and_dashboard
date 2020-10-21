<?php

namespace App\Traits;

use App\Ad;
use App\AdArea;
use App\Agreement;
use App\Area;
use App\Lead;
use App\Property;
use App\PropertyFeature;
use App\PropertyImage;
use App\PropertyLocation;
use App\PropertyVideo;
use App\Session;
use App\User;
use App\Visit;
use Stripe\Plan;
use Stripe\Stripe;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;

trait ApiDatabase
{
    use ApiHelper, ApiValidation;

    /**
     * Custom Database Factory
     *
     * @param $model
     * @param int $quantity
     */
    public function customFactory($model, int $quantity = 0)
    {
        $this->command->line("<fg=yellow>Generating:</> {$model} <fg=yellow>Quantity:</> {$quantity}");

        switch ($model) {
            case User::class:
                factory(User::class, $quantity)->create();
                break;
            case Property::class:
                factory(Property::class, $quantity)->create();
                break;
            case PropertyLocation::class:
                factory(PropertyLocation::class, $quantity)->create();
                break;
            case PropertyFeature::class:
                factory(PropertyFeature::class, $quantity)->create();
                break;
            case PropertyImage::class:
                factory(PropertyImage::class, $quantity)->create();
                break;
            case PropertyVideo::class:
                factory(PropertyVideo::class, $quantity)->create();
                break;
            case Ad::class:
                factory(Ad::class, $quantity)->create();
                break;
            case Lead::class:
                factory(Lead::class, $quantity)->create();
                break;
            case Area::class:
                factory(Area::class, $quantity)->create();
                break;
            case AdArea::class:
                $this->factoryAdArea();
                break;
            case Agreement::class:
                $this->factoryAgreement($quantity);
                break;
            case Visit::class:
                factory(Visit::class, $quantity)->create();
                break;
            case Session::class:
                factory(Session::class, $quantity)->create();
                break;
            default:
                break;
        }

        $this->command->line("<fg=green>Generated:</> {$model}");
    }

    /**
     * Custom factory for AdArea model
     */
    private function factoryAdArea()
    {
        $areas = Area::all();
        $ads = Ad::all();

        $ads->each(function ($ad) use ($areas) {
            $ad->areas()->attach($areas->random(rand(1, count($areas)))->pluck('id')->toArray());
        });
    }

    /**
     * Custom factory for Agreement model
     *
     * @param int $quantity
     * @return bool
     */
    private function factoryAgreement(int $quantity)
    {
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');

        $this->checkIfStripeKeysExists($stripeKey, $stripeSecret);

        Stripe::setApiKey($stripeSecret);
        $stripeTokens = collect([
            'tok_visa',
            'tok_visa_debit',
            'tok_mastercard',
            'tok_discover',
            'tok_chargeDeclined',
            'tok_chargeDeclinedInsufficientFunds'
        ]);

        try {
            $stripePlans = Plan::all();
        } catch (Exception $e) {
            return true;
        }

        $plans = collect();

        foreach ($stripePlans as $plan) {
            $plans->push($plan);
        }

        $countUsers = User::count();

        /* Similar to StripeSubscriptionController@store */
        for ($x = 0; $x <= $quantity; $x++) {
            $now = Carbon::now();
            $createdAt = $now->subDays(rand(0, 30))->toDateTimeString();
            $agreement = collect();

            try {
                $user = User::find(rand(1, $countUsers));
                $plan = $plans->random();

                $this->command->line("<fg=yellow>Generating:</> " . trans('response.trying_to_charge_for_plan', ['plan_id' => $plan->id, 'email' => $user->email]));

                $agreement = $user->activeAgreements()->first();

                if (!empty($agreement)) {
                    throw new HttpException(422, 'Failed: ' . trans('response.the_user_has_an_active_agreement_until', ['type' => $agreement->type, 'to' => $agreement->to_date, 'id' => $agreement->id]));
                }

                $type = collect([Agreement::TYPE_REGULAR, Agreement::TYPE_TRIAL])->random();

                $agreement = Agreement::create([
                    'developer_id' => $user->id,
                    'from' => null,
                    'to' => null,
                    'type' => $type,
                    'trial_starts_at' => null,
                    'trial_ends_at' => null,
                    'stripe_plan' => $plan->id,
                    'price' => (float)($plan->amount / 100),
                    'currency' => $plan->currency,
                    'verified' => Agreement::UNVERIFIED_AGREEMENT,
                    'status' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt
                ]);

                switch ($type) {
                    case Agreement::TYPE_REGULAR:
                        $agreement->from = $createdAt;
                        $agreement->to = Carbon::createFromTimestamp(strtotime("{$agreement->from} +{$plan->interval_count} {$plan->interval}s"))->toDateTimeString();
                        break;
                    case Agreement::TYPE_TRIAL:
                        $agreement->trial_starts_at = $createdAt;
                        $trialDuration = rand(1, 2) . ' weeks';
                        $agreement->trial_ends_at = Carbon::createFromTimestamp(strtotime("{$agreement->from} +{$trialDuration}"))->toDateTimeString();
                        break;
                }

                $charge = $user->charge($plan->amount, [
                    'description' => trans('response.test_charge_for_plan', ['plan_id' => $plan->id, 'email' => $user->email]),
                    'source' => $stripeTokens->random()
                ]);

                $agreement->stripe_charge = $charge->id;
                $agreement->verified = Agreement::VERIFIED_AGREEMENT;
                $agreement->save();
                $this->command->line("<fg=green>Generated:</> OK");
            } catch (Exception $e) {
                $this->command->line('<fg=red>Failed:</> ' . $e->getMessage());
                $agreement->verified = Agreement::UNVERIFIED_AGREEMENT;
                $agreement->status = $e->getMessage();
                $agreement->save();
            }
        }
    }
}