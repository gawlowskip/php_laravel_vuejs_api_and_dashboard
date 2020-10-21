<?php

namespace App\Http\Controllers\Stat;

use App\Agreement;
use App\Developer;
use App\Http\Controllers\ApiController;
use App\Property;
use App\Session;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use stdClass;

class StatController extends ApiController
{
    /**
     * GET /api/stats/projects-created
     * Get number of created properties by all Developers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function projectsCreated(Request $request)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $created = collect();
        $total = 0;
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $countProperties = Property::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
            $created->push([
                'x' => $fromDate->toDateString(),
                'y' => $countProperties
            ]);
            $total += $countProperties;
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $results = new stdClass();
        $results->data = [
            'created' => $created,
            'total' => $total
        ];

        return $this->successResponse($results, 200);
    }

    /**
     * GET /api/stats/projects-viewed/{developer?}
     * Get number of viewed properties or Get number of viewed of specified Developer properties
     *
     * @param Request $request
     * @param null $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function projectsViewed(Request $request, $developer = null)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        if ($developer) {
            $developer = Developer::findOrFail($developer);
        }

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $viewed = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $countVisits = Visit::where('visitable_type', Property::class)->whereBetween('created_at', [$startOfDay, $endOfDay]);

            if (isset($developer->id)) {
                $developerPropertyIds = $developer->properties->pluck('id')->toArray();
                $countVisits = $countVisits->whereIn('visitable_id', $developerPropertyIds);
            }

            $countVisits = $countVisits->count();
            $viewed->push([
                'x' => $fromDate->toDateString(),
                'y' => $countVisits
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $results = new stdClass();
        $results->data = [
            'viewed' => $viewed
        ];

        return $this->successResponse($results, 200);
    }

    /**
     * GET /api/stats/projects-who-viewed/{developer?}
     * Get list of User who viewed properties or Get list of User who viewed properties for specified Developer
     *
     * @param Request $request
     * @param null $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function projectsWhoViewed(Request $request, $developer = null)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        if ($developer) {
            $developer = Developer::findOrFail($developer);
        }

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $whoViewed = collect();
        $guest = [
            'id' => null,
            'name' => 'Guest',
            'last_name' => '',
            'visits' => 0
        ];
        $whoViewed->push($guest);

        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $visits = Visit::where('visitable_type', Property::class)->whereBetween('created_at', [$startOfDay, $endOfDay]);

            if (isset($developer->id)) {
                $developerPropertyIds = $developer->properties->pluck('id')->toArray();
                $visits = $visits->whereIn('visitable_id', $developerPropertyIds);
            }

            $visits = $visits->get();

            foreach ($visits as $visit) {
                $user = $visit->user;

                if (empty($user)) {
                    $guest['visits'] += 1;
                    continue;
                }

                $userId = $user->id;
                $userName = $user->name;
                $userLastName = $user->last_name;

                $exists = $whoViewed->search(function ($item, $key) use ($userId) {
                    return $item['id'] == $userId;
                }, false);

                if (!$exists) {
                    $whoViewed->push([
                        'id' => $userId,
                        'name' => $userName,
                        'last_name' => $userLastName,
                        'visits' => 1
                    ]);
                } else {
                    $index = $whoViewed->search(function ($item, $key) use ($userId) {
                        return $item['id'] == $userId;
                    });
                    $existingItem = $whoViewed[$index];
                    $existingItem['visits'] += 1;
                    $whoViewed[$index] = $existingItem;
                }
            }
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        if ($guest['visits'] > 0) {
            $whoViewed[0] = $guest;
        } else {
            unset($whoViewed[0]);
        }

        $whoViewed = $whoViewed->sortByDesc('visits');
        $whoViewed = $whoViewed->values()->all();

        $results = new stdClass();
        $results->data = [
            'who_viewed' => $whoViewed
        ];

        return $this->successResponse($results, 200);
    }

    /**
     * GET /api/stats/users-logins-and-signups
     * Get number of User login's and signup's
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function usersLoginsAndSignups(Request $request)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $logins = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->timestamp;
            $endOfDay = $fromDate->endOfDay()->timestamp;
            $countLogins = Session::whereBetween('last_activity', [$startOfDay, $endOfDay])->whereNotNull('user_id')->count();
            $logins->push([
                'x' => $fromDate->toDateString(),
                'y' => $countLogins
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate))->addDay();

        $signups = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $countSignups = User::whereBetween('created_at', [$startOfDay, $endOfDay])->count();
            $signups->push([
                'x' => $fromDate->toDateString(),
                'y' => $countSignups
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $results = new stdClass();
        $results->data = [
            'logins' => $logins,
            'signups' => $signups
        ];

        return $this->successResponse($results, 200);
    }

    /**
     * GET stats/developers-without-agreement
     * Get number of Developers without Agreement
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function developersWithoutAgreement(Request $request)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $users = User::all();
        $developersWithoutAgreement = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();

            $countDevelopersWithoutAgreement = 0;

            foreach ($users as $user) {
                $hasProperty = Property::where('developer_id', $user->id)->where('created_at', '<=', $endOfDay)->exists();
                $hasActiveAgreement = Agreement::where('developer_id', $user->id)->where('from', '<=', $endOfDay)->where('to', '>=', $endOfDay)->withTrashed()->count();

                if (!$hasProperty && !$hasActiveAgreement || ($hasProperty && !$hasActiveAgreement)) {
                    $countDevelopersWithoutAgreement++;
                }
            }

            $developersWithoutAgreement->push([
                'x' => $fromDate->toDateString(),
                'y' => $countDevelopersWithoutAgreement
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $results = new stdClass();
        $results->data = [
            'without_agreement' => $developersWithoutAgreement,
        ];

        return $this->successResponse($results, 200);
    }

    /**
     * GET /api/stats/payments-verified-and-unverified
     * Get the number of payments verified and unverified
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function paymentsVerifiedAndUnverified(Request $request)
    {
        $rules = [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $this->validate($request, $rules);

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate));

        $this->checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, 14);

        $toDate = $toDate->addDay();

        $paymentsVerified = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $countPaymentsVerified = Agreement::where('verified', 1)->whereBetween('created_at', [$startOfDay, $endOfDay])->count();
            $paymentsVerified->push([
                'x' => $fromDate->toDateString(),
                'y' => $countPaymentsVerified
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate));
        $toDate = Carbon::createFromTimestamp(strtotime($request->toDate))->addDay();
        $paymentsUnverified = collect();
        do {
            $startOfDay = $fromDate->startOfDay()->toDateTimeString();
            $endOfDay = $fromDate->endOfDay()->toDateTimeString();
            $countPaymentsUnverified = Agreement::where('verified', 0)->whereBetween('created_at', [$startOfDay, $endOfDay])->count();
            $paymentsUnverified->push([
                'x' => $fromDate->toDateString(),
                'y' => $countPaymentsUnverified
            ]);
            $fromDate->addDay();
        } while ($fromDate->lte($toDate));

        $results = new stdClass();
        $results->data = [
            'verified' => $paymentsVerified,
            'unverified' => $paymentsUnverified
        ];

        return $this->successResponse($results, 200);
    }
}