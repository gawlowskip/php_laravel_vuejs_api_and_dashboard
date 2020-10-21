<?php

namespace App\Http\Controllers\Developer;

use App\Developer;
use App\Http\Controllers\ApiController;
use App\Http\Resources\Property as PropertyResource;

class DeveloperPropertyController extends ApiController
{
    /**
     * GET /api/developers/{developer}/properties
     * Get all Property for the Developer (sortable, filterable, pagination)
     *
     * @param $developer
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Developer $developer)
    {
        /* Pattern - paginate data */
//        $properties = $developer->properties();
//        $properties = $this->sortPaginatedData($properties);
//        $properties = $this->filterPaginatedData($properties);
//        $properties = $properties->paginate(5);

        /* Pattern - collection */
        $properties = $developer->properties;
        $properties = $this->sortData($properties);
        $properties = $this->filterData($properties);

        return PropertyResource::collection($properties);
    }
}
