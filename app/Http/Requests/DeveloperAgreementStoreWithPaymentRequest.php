<?php

namespace App\Http\Requests;

use App\Agreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperAgreementStoreWithPaymentRequest extends FormRequest
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
            'stripe_plan_id' => 'required',
            'user_id' => 'required',
            'token' => 'required',
            'type' => ['sometimes', Rule::in([Agreement::TYPE_REGULAR, Agreement::TYPE_TRIAL])],
        ];
    }
}
