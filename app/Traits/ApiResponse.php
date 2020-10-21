<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait ApiResponse
{
    /**
     * Success response
     *
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    /**
     * Error response
     *
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Check if we are on frontend
     *
     * @param $request
     * @return bool
     */
    protected function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    /**
     * Sort data
     *
     * @param $collection
     * @return Collection
     */
    protected function sortData($collection)
    {
        if (request()->has('sort_by_asc')) {
            $attribute = request()->sort_by_asc;
            $collection = $collection->sortBy->{$attribute};
        }

        if (request()->has('sort_by_desc')) {
            $attribute = request()->sort_by_desc;
            $collection = $collection->sortByDesc->{$attribute};
        }

        return $collection;
    }

    /**
     * Sort paginated data
     *
     * @param $query
     * @return mixed
     */
    protected function sortPaginatedData($query)
    {
        if (request()->has('sort_by_asc')) {
            $method = 'asc';
            $attributes = request()->sort_by_asc;
            $attributes = explode(",", $attributes);
            foreach ($attributes as $attribute) {
                $attribute = trim($attribute);
                $query = $query->orderBy($attribute, $method);
            }
        }

        if (request()->has('sort_by_desc')) {
            $method = 'desc';
            $attributes = request()->sort_by_desc;
            $attributes = explode(",", $attributes);
            foreach ($attributes as $attribute) {
                $attribute = trim($attribute);
                $query = $query->orderBy($attribute, $method);
            }
        }

        return $query;
    }

    /**
     * Filter data
     *
     * @param $collection
     * @return Collection|static
     */
    protected function filterData($collection)
    {
        foreach (request()->query() as $query => $value) {
            if (isset($query, $value) && $query != 'sort_by_desc' && $query != 'sort_by_asc' && $query != 'page' && $query != 'per_page') {
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    /**
     * Filter paginated data
     *
     * @param $mainQuery
     * @return mixed
     */
    protected function filterPaginatedData($mainQuery)
    {
        foreach (request()->query() as $query => $value) {
            if (isset($query, $value) && $query != 'sort_by_desc' && $query != 'sort_by_asc' && $query != 'page' && $query != 'per_page') {
                $mainQuery = $mainQuery->where($query, $value);
            }
        }

        return $mainQuery;
    }
}