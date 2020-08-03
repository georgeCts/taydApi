<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

class ServiceValidator extends FormRequest
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
            //
        ];
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id'               => 'required|integer|exists:users,id',
            'user_property_id'      => 'required|integer|exists:users_properties,id',
            'stripe_customer_source_id' => 'required|integer|exists:stripe_customers_sources,id',
            'date'                  => 'required|date_format:Y-m-d',
            'time'                  => 'required|date_format:H:i:s',
            'has_consumables'       => 'required|boolean',
            'service_cost'          => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount'              => 'nullable|integer'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
