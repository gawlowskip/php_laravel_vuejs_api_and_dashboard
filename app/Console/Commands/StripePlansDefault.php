<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Subscription;

class StripePlansDefault extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate default Stripe plans';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Stripe\Error\Api
     */
    public function handle()
    {
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');
        Stripe::setApiKey($stripeSecret);

        $this->line("API - running...");
        $this->info('php artisan stripe:plans');
        $this->line("STRIPE_KEY: {$stripeKey}");
        $this->line("STRIPE_SECRET: {$stripeSecret}");

        $plans = Plan::all();
        $countPlans = count($plans->data);
        if ($countPlans) {
            foreach ($plans->data as $plan) {
                $subscriptions = Subscription::all([
                    'plan' => $plan->id,
                ]);
                $countSubscriptions = count($subscriptions->data);
                if ($countSubscriptions) {
                    $this->info("Plan {$plan->id} has {$countSubscriptions} subscriptions.");
                } else {
                    $this->info("Plan {$plan->id} has not any subscriptions.");
                }
            }

            if ($this->confirm('Do you wish to delete existing plans?')) {

                foreach ($plans->data as $plan) {
                    $subscriptions = Subscription::all([
                        'plan' => $plan->id,
                    ]);
                    $countSubscriptions = count($subscriptions->data);

                    if ($countSubscriptions) {
                        $this->info("Plan {$plan->id} has {$countSubscriptions} subscriptions.");
                        foreach ($subscriptions->data as $subscription) {
                            if ($this->confirm("Do you wish to cancel subscription {$subscription->id}?")) {
                                $subscription = Subscription::retrieve($subscription->id);
                                $subscription->delete();
                                $this->info("Subscription {$subscription->id} has been canceled");
                            }
                        }
                    }

                    if ($this->confirm("Do you wish to delete plan {$plan->id}?")) {
                        $plan = Plan::retrieve($plan->id);
                        $plan->delete();
                        $this->info("Plan {$plan->id} has been deleted");
                    }
                }

            }
        } else {
            $this->info("Current Stripe account has not any plans");
        }

        if ($this->confirm('Do you wish to create default plans?')) {
            $currency = $this->ask('Plan currency: ', 'usd');

            $weekly = Plan::create([
                "metadata" => [
                    "name" => "Weekly Plan",
                    "description" => "Plan for 1 week - 10 DKK"
                ],
                "amount" => 1000,
                "currency" => $currency,
                "interval" => "week",
                "interval_count" => 1,
                "product" => [
                    "name" => "Weekly Plan",
                ],
            ]);
            $this->info("Plan {$weekly->id} has been created");

            $monthlyPlan = Plan::create([
                "metadata" => [
                    "name" => "Monthly Plan",
                    "description" => "Plan for 1 month - 30 DKK"
                ],
                "amount" => 3000,
                "currency" => $currency,
                "interval" => "month",
                "interval_count" => 1,
                "product" => [
                    "name" => "Monthly Plan",
                ],
            ]);
            $this->info("Plan {$monthlyPlan->id} has been created");

            $threeMonthsPlan = Plan::create([
                "metadata" => [
                    "name" => "Three Months Plan",
                    "description" => "Plan for 3 month - 40 DKK"
                ],
                "amount" => 4000,
                "currency" => $currency,
                "interval" => "month",
                "interval_count" => 3,
                "product" => [
                    "name" => "Three Months Plan",
                ],
            ]);
            $this->info("Plan {$threeMonthsPlan->id} has been created");

            $yearlyPlan = Plan::create([
                "metadata" => [
                    "name" => "Yearly Plan",
                    "description" => "Plan for 1 year - 50 DKK"
                ],
                "amount" => 5000,
                "currency" => $currency,
                "interval" => "year",
                "interval_count" => 1,
                "product" => [
                    "name" => "Yearly Plan",
                ],
            ]);
            $this->info("Plan {$yearlyPlan->id} has been created");
        }
    }
}
