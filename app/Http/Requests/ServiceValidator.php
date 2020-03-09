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
            'request_user_id'       => 'required|integer|exists:users,id',
            'provider_user_id'      => 'required|integer|exists:users,id',
            'user_property_id'      => 'required|integer|exists:users_properties,id',
            'is_accepted'           => 'required|boolean',
            'dt_start'              => 'required|date_format:Y-m-d H:i:s',
            'has_consumables'       => 'required|boolean'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
