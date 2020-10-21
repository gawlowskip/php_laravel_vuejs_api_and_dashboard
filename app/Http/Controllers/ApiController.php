<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\ApiHelper;
use App\Traits\ApiValidation;

class ApiController extends Controller
{
    use ApiResponse, ApiHelper, ApiValidation;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api');
    }
}
