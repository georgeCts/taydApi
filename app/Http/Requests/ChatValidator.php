<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

class ChatValidator extends FormRequest
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
            'service_id'        => 'required|integer|exists:services,id',
            'message'           => 'required|string',
            'fromTayder'        => 'required|boolean',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }
}
