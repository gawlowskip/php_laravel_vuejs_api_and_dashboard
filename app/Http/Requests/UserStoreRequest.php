<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'sometimes',
            'architect_name' => 'sometimes',
            'password' => 'sometimes|min:6',
            'facebook_id' => 'sometimes|nullable|unique:users',
            'street_1' => 'sometimes',
            'street_2' => 'sometimes',
            'city' => 'sometimes',
            'postal_code' => 'sometimes',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
            'cvr_number' => 'sometimes',
            'active' => 'sometimes|boolean',
        ];
    }
}
