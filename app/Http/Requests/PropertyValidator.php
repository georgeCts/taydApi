<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

class PropertyValidator extends FormRequest
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
            'user_id'           => 'required|integer|exists:users,id',
            'name'              => 'required|string|max:200',
            'latitude'          => 'required|string|max:20',
            'altitude'          => 'required|string|max:20',
            'rooms_qty'         => 'required|integer',
            'bathrooms_qty'     => 'required|integer',
            'has_living_room'   => 'required|boolean',
            'has_dinning_room'  => 'required|boolean',
            'has_kitchen'       => 'required|boolean',
            'has_garage'        => 'required|boolean',
            'has_backyard'      => 'required|boolean',
            'floors_qty'        => 'required|integer',
            'property_type_id'  => 'required|integer|exists:properties_types,id'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
