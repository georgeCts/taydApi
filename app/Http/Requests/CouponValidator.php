<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

class CouponValidator extends FormRequest
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
        $validator  = Validator::make($request->all(), [
            'code'              => 'required|string|max:15',
            'title'             => 'required|string|max:200',
            'description'       => 'required|string|max:200',
            'value'             => 'required|string',
            'free_service'      => 'nullable|boolean',
            'discount_service'  => 'nullable|boolean',
            'start'             => 'required|date_format:Y-m-d',
            'end'               => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }

    public function update(Request $request) {
        $validator  = Validator::make($request->all(), [
            'id'                => 'required|integer|exists:coupons,id',
            'code'              => 'required|string|max:15',
            'title'             => 'required|string|max:200',
            'description'       => 'required|string|max:200',
            'value'             => 'required|string',
            'free_service'      => 'nullable|boolean',
            'discount_service'  => 'nullable|boolean',
            'start'             => 'required|date_format:Y-m-d',
            'end'               => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
