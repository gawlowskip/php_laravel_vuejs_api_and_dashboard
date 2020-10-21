<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdStoreRequest extends FormRequest
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
            'developer_id' => 'required',
            'from_date' => 'sometimes',
            'to_date' => 'sometimes',
            'active' => 'sometimes',
            'image' => 'sometimes',
            'external_image_url' => 'sometimes',
            'url' => 'sometimes',
            'price' => 'sometimes',
            'price_lead' => 'sometimes',
            'seconds' => 'sometimes',
            'areas' => 'sometimes|array',
        ];
    }
}
