<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyFeatureStoreRequest extends FormRequest
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
            'property_type' => 'sometimes',
            'material' => 'sometimes',
            'completion_date' => 'sometimes',
            'size' => 'sometimes',
            'rooms_amount' => 'sometimes',
            'baths_amount' => 'sometimes',
            'bedrooms_amount' => 'sometimes',
            'floors' => 'sometimes',
            'price' => 'sometimes'
        ];
    }
}
