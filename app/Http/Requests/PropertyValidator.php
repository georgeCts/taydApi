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
            'living_room_qty'   => 'required|integer',
            'dinning_room_qty'  => 'required|integer',
            'kitchen_qty'       => 'required|integer',
            'garage_qty'        => 'required|integer',
            'backyard_qty'      => 'required|integer',
            'floors_qty'        => 'required|integer',
            'property_type_id'  => 'required|integer|exists:properties_types,id'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
