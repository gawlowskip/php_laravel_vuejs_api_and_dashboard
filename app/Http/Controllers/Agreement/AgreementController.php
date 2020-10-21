<?php

namespace App\Http\Controllers\Agreement;

use App\Agreement;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Agreement as AgreementResource;

class AgreementController extends ApiController
{
    /**
     * GET /api/agreements
     * Get all Agreements (sortable, filterable, pagination)
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        /* Pattern - paginate data */
//        $agreements = Agreement::where('id', '!=', null);
//        $agreements = $this->sortPaginatedData($agreements);
//        $agreements = $this->filterPaginatedData($agreements);
//        $agreements = $agreements->paginate(5);

        /* Pattern - collection */
        $agreements = Agreement::withTrashed();
        $agreements = $this->sortData($agreements);
        $agreements = $this->filterData($agreements);
        $agreements = $agreements->get();

        return AgreementResource::collection($agreements);
    }

    /**
     * GET /api/agreement/{agreement}
     * Get the Agreement data
     *
     * @param $agreement
     * @return AgreementResource
     */
    public function show($agreement)
    {
        $agreement = Agreement::withTrashed()->findOrFail($agreement);

        return new AgreementResource($agreement);
    }
}
