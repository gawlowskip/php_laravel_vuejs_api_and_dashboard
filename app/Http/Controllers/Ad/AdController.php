<?php

namespace App\Http\Controllers\Ad;

use App\Ad;
use App\Area;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AdStoreRequest;
use App\Http\Resources\Area as AreaResource;
use App\Http\Resources\Developer as DeveloperResource;
use App\Http\Resources\Lead as LeadResource;
use App\Traits\ApiResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;
use App\Http\Resources\Ad as AdResource;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdController extends ApiController
{
    use ApiResponse;

    /**
     * GET /api/ads
     * Get all Ads (sortable, filterable, pagination)
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            /* Filter ads by location (latitude and longitude) */
            $areaIds = $this->findAreasByLatitudeAndLongitude($request);
            $ads = Ad::whereHas('areas', function ($query) use ($areaIds) {
                $query->whereIn('area_id', $areaIds);
            })->whereHas('developer', function ($query) {
                $query->where('active', User::ACTIVE);
            });

            unset($request['latitude']);
            unset($request['longitude']);

            $ads = $this->sortData($ads);
            $ads = $this->filterData($ads);
            $ads = $ads->get();
        } else {
            $ads = Ad::whereHas('developer', function ($query) {
                $query->where('active', User::ACTIVE);
            });
            $ads = $this->sortData($ads);
            $ads = $this->filterData($ads);
            $ads = $ads->get();
        }

        return AdResource::collection($ads);
    }

    /**
     * GET /api/ads/{ad}
     * Get Ad details
     *
     * @param Ad $ad
     * @return AdResource|\Illuminate\Http\JsonResponse
     */
    public function show(Ad $ad)
    {
        return new AdResource($ad);
    }

    /**
     * POST /api/ads
     * Store new Ad
     *
     * @param AdStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdStoreRequest $request)
    {
        $developer = User::findOrFail($request->developer_id);

        $this->checkIfUserActive($developer);

        $data = $request->all();

        if ($request->has('image')) {
            $image = $request->file('image');
            unset($data['image']);
        }

        if (!$request->has('seconds')) {
            $data['seconds'] = 5;
        }

        if ($request->has('areas')) {
            unset($data['areas']);
        }

        $ad = $developer->ads()->create($data);

        if ($request->has('image')) {
            if ($this->fileIsEncodedInBase64($request->get('image'))) {
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $filename = $developer->id . '_' . $ad->id . '_' . strtotime('now') . '_' . rand(100000, 999999) . '.png';
                Storage::disk('ads')->put($filename, base64_decode($image));
                $ad->image = $filename;
                $ad->save();
            } else {
                $image = $request->file('image');
                $filename = Storage::disk('ads')->put('', $image);
                $ad->image = $filename;
                $ad->save();
            }
        }

        if ($request->has('areas')) {
            $areas = Area::all()->pluck('id')->toArray();
            foreach($request->areas as $areaId) {
                if (in_array($areaId, $areas)) {
                    $ad->areas()->attach($areaId);
                }
            }
        }

        return (new AdResource($ad))->response()->setStatusCode(201);
    }

    /**
     * GET /api/ad
     * Get the random Ad
     *
     * @return AdResource|\Illuminate\Http\JsonResponse
     */
    public function random()
    {
        $ads = Ad::whereHas('developer', function ($query) {
            $query->where('active', User::ACTIVE);
        })->get();

        if (!count($ads)) {
            $ad = new stdClass();
            $ad->data = collect();
            return $this->successResponse($ad, 200);
        }

        $ad = $ads->random();

        return new AdResource($ad);
    }

    /**
     * GET /api/ads/{ad}/areas
     * Get the Areas for the Ad
     *
     * @param Ad $ad
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAdAreas(Ad $ad)
    {
        $areas = $ad->areas;

        return AreaResource::collection($areas);
    }

    /**
     * GET /api/ads/{ad}/developer
     * Get the Developer for the Ad
     *
     * @param Ad $ad
     * @return DeveloperResource
     */
    public function getAdDeveloper(Ad $ad)
    {
        $developer = $ad->developer;

        return new DeveloperResource($developer);
    }

    /**
     * GET /api/ads/{ad}/leads
     * Get the Leads for the Ad
     *
     * @param Request $request
     * @param Ad $ad
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAdLeads(Request $request, Ad $ad)
    {
        $fromDate = Carbon::now()->subYears(10)->toDateTimeString();
        if ($request->has('fromDate')) {
            try {
                $fromDate = Carbon::createFromTimestamp(strtotime($request->fromDate))->toDateTimeString();
            } catch (Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        $toDate = Carbon::now()->addYears(10)->toDateTimeString();
        if ($request->has('toDate')) {
            try {
                $toDate = Carbon::createFromTimestamp(strtotime($request->toDate))->addDay()->toDateTimeString();
            } catch (Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        $leads = $ad->leads()->whereBetween('clicked_on', [$fromDate, $toDate])->get();

        return LeadResource::collection($leads);
    }
}