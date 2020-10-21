<?php

namespace App\Http\Controllers\Developer;

use App\Ad;
use App\Area;
use App\Developer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DeveloperAdStoreRequest;
use App\Http\Requests\DeveloperAdUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Http\Resources\Ad as AdResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeveloperAdController extends ApiController
{
    /**
     * GET /api/developers/{developer}/ads
     * Get the Ads for the Developer (sortable, filterable, pagination)
     *
     * @param Request $request
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Developer $developer)
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            /* Filter ads by location (latitude and longitude) */
            $areaIds = $this->findAreasByLatitudeAndLongitude($request);
            $ads = $developer->ads()->whereHas('areas', function ($query) use ($areaIds) {
                $query->whereIn('area_id', $areaIds);
            });

            unset($request['latitude']);
            unset($request['longitude']);

            $ads = $this->sortData($ads);
            $ads = $this->filterData($ads);
            $ads = $ads->get();
        } else {
            $ads = $developer->ads();
            $ads = $this->sortData($ads);
            $ads = $this->filterData($ads);
            $ads = $ads->get();
        }

        return AdResource::collection($ads);
    }

    /**
     * POST /api/developers/{developer}/ads
     * Store the Ad for the Developer
     *
     * @param DeveloperAdStoreRequest $request
     * @param Developer $developer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(DeveloperAdStoreRequest $request, Developer $developer)
    {
        /* TODO: It can be a situation when developer will not be able to create new add. Switch to POST /ads */
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
     * GET /api/developers/{developer}/ads/{ad}
     * Get the Ad data for the Developer
     *
     * @param Developer $developer
     * @param Ad $ad
     * @return AdResource
     */
    public function show(Developer $developer, Ad $ad)
    {
        $this->checkIfDeveloperIsOwner($developer, $ad);

        return new AdResource($ad);
    }

    /**
     * PUT /api/developers/{developer}/ads/{ad}
     * Update the Ad for the Developer
     *
     * @param DeveloperAdUpdateRequest $request
     * @param Developer $developer
     * @param Ad $ad
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(DeveloperAdUpdateRequest $request, Developer $developer, Ad $ad)
    {
        $this->checkIfDeveloperIsOwner($developer, $ad);

        $ad->fill($request->only([
            'from_date',
            'to_date',
            'active',
            'external_image_url',
            'url',
            'price',
            'price_lead',
            'seconds',
        ]));

        $ad->save();

        if ($request->has('image')) {
            if ($request->image == null) {
                Storage::disk('ads')->delete($ad->getOriginal('image'));
                $ad->image = null;
            } else {
                Storage::disk('ads')->delete($ad->getOriginal('image'));

                if ($this->fileIsEncodedInBase64($request->get('image'))) {
                    $image = $request->file('image');
                    $image = str_replace('data:image/png;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $filename = $developer->id . '_' . $ad->id . '_' . strtotime('now') . '_' . rand(100000, 999999) . '.png';
                    Storage::disk('ads')->put($filename, base64_decode($image));
                    $ad->image = $filename;
                } else {
                    $image = $request->file('image');
                    $filename = Storage::disk('ads')->put('', $image);
                    $ad->image = $filename;
                }
            }
            $ad->save();
        }

        if (!$ad->seconds) {
            $ad->seconds = 5;
            $ad->save();
        }

        if ($request->has('areas')) {
            $ad->areas()->detach();
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
     * DELETE /api/developers/{developer}/ads/{ad}
     * Destroy the Ad for the Developer
     *
     * @param Developer $developer
     * @param Ad $ad
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(Developer $developer, Ad $ad)
    {
        $this->checkIfUserIsAdOwner($developer, $ad);

        $ad->delete();
        if ($ad->image != null) {
            Storage::disk('ads')->delete($ad->getOriginal('image'));
        }

        return (new AdResource($ad))->response()->setStatusCode(200);
    }

    /**
     * GET /api/developers/{developer}/ads/{ad}/image
     * Destroy the Image included in the Ad
     *
     * @param Developer $developer
     * @param Ad $ad
     */
    public function destroyImage(Developer $developer, Ad $ad)
    {
        $this->checkIfUserIsAdOwner($developer, $ad);
        $this->checkIfAdHasImage($ad);

        Storage::disk('ads')->delete($ad->getOriginal('image'));
        $ad->image = null;
        $ad->save();
    }
}
