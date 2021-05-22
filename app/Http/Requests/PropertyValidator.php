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
        $response   = true;
        $validator  = Validator::make($request->all(), [
            'user_id'           => 'required|integer|exists:users,id',
            'property_type_id'  => 'required|integer|exists:properties_types,id',
            'name'              => 'required|string|max:200',
            'address'           => 'required|string|max:200',
            'reference'         => 'required|string|max:200',
            'latitude'          => 'required|string|max:20',
            'altitude'          => 'required|string|max:20',
            'distribution'      => 'nullable|array'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        if(isset($request->distribution) && !is_null($request->distribution)){
            $response = $this->propertyDistribution($request->distribution);
        }

        return $response;
    }

    private function propertyDistribution($data) {
        for($i = 0; $i < count($data); $i++) {
            $valor      = (array)$data[$i];
            $detalle    = Validator::make($valor, [
                'property_type_price_id'    => 'required|integer|exists:properties_types_prices,id',
                'quantity'                  => 'required|integer',
            ]);
            
            if ($detalle->fails()) {
                return response()->json($detalle->errors());
            }
        }

        return true;
    }
}
