<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Validator;

class UserValidator extends FormRequest
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

    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string|email|unique:users|max:191',
            'password'  => 'required|string|max:200',
            'confirmed' => 'nullable|boolean',
            'isTayder'  => 'nullable|boolean'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        return true;
    }

    public function uploadDocument(Request $request) {
        $datos = $request->all();
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|integer|exists:users,id',
            'name'      => 'required|string',
            /* 'file'      => 'required|file|mimes:jpeg,jpg,png' */
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        return true;
    }
}
