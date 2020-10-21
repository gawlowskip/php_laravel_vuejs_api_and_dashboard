<?php

namespace App\Http\Requests;

use App\Agreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperAgreementUpdateRequest extends FormRequest
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
            'from' => 'sometimes|nullable|date',
            'to' => 'sometimes|nullable|date',
            'type' => ['required', Rule::in([Agreement::TYPE_REGULAR, Agreement::TYPE_TRIAL])],
            'trial_starts_at' => 'sometimes|nullable|date',
            'trial_ends_at' => 'sometimes|nullable|date|after:trial_starts_at',
            'stripe_plan' => 'required',
            'stripe_charge' => 'sometimes',
            'price' => 'sometimes|min:0',
            'currency' => 'sometimes',
            'verified' => 'sometimes|boolean',
            'status' => 'sometimes'
        ];
    }
}
