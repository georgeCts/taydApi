<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CouponValidator;
use App\Coupon;
use Exception;

class CouponController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;

    public function __construct(CouponValidator $validaciones) {
        $this->validaciones = $validaciones;
    }

    public function store(Request $request) {
        $validacion = $this->validaciones->store($request);
        $data       = $request->all();

        if($validacion !== true) {
            return response()->json(['error'=> $validacion->original], 403);
        }

        if(isset($data["id"]) || is_null($request->id)) {
            unset($data['id']);
        }

        $objCoupon = Coupon::create($data);

        return response()->json($objCoupon, 200);
    }

    public function update(Request $request) {
        $response = array('message' => 'Error');
        $codigo = 403;
        
        try {
            $validacion = $this->validaciones->update($request);
            $data       = $request->all();

            if($validacion !== true) {
                return response()->json(['error'=> $validacion->original], 403);
            }

            Coupon::where('id', $request->id)->update($data);
            $response["message"]    = "Operacion Exitosa";
            $codigo                 = 200;
        }
        catch(Exception $ex){
            $response["message"] = $ex->errorInfo[2];
        }
        return response()->json($response, $codigo);
    }
}
