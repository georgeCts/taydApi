<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\UserValidator;
use App\Services\FileService;
use App\UserDocument;
use App\User;
use App\UserCoupon;
use App\Coupon;
use Exception;
USE DB;

class UserController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;
    protected $servicios;

    public function __construct(UserValidator $validaciones, FileService $servicios) {
        $this->validaciones = $validaciones;
        $this->servicios    = $servicios;
    }

    public function get($id){
        $user = User::find($id);

        if(is_null($user)) {
            return response()->json( ['error'=> "No se encontro el usuario con id ".$id], 403);
        }

        $user->info;
        $user->documents;
        $user->properties;

        return response()->json($user, 200);
    }

    /**
     * USER COUPONS
     */
    public function getCoupons($id) {
        $query = DB::table('users_coupons as uc');
        $query->join('coupons', 'uc.coupon_id', '=', 'coupons.id')
              ->select('coupons.*', 'uc.id as user_coupon_id');

        $coupons = $query->where('uc.user_id', $id)
                        ->where('uc.active', true)
                        ->where('coupons.end', '>=', Carbon::now()->format('Y-m-d'))
                        ->orderBy('uc.coupon_id', 'DESC')
                        ->get();

        return response()->json($coupons, 200);
    }

    public function setCoupons(Request $request, $id) {
        $coupon = Coupon::where("code", $request->code)
                        ->where("active", true)
                        ->where("end", ">=", Carbon::now()->format('Y-m-d'))
                        ->first();

        if(is_null($coupon)) {
            return response()->json(['error'=> "El código de cupón: " . $request->code . " no es válido o ha expirado."], 403);
        }

        $userCoupon = UserCoupon::where("user_id", $id)
                            ->where("coupon_id", $coupon->id)
                            ->first();

        if(!is_null($userCoupon)) {
            return response()->json(['error'=> "Este cupón ya se encuentra registrado."], 403);
        }

        try {
            $objUserCoupon = new UserCoupon();
            $objUserCoupon->user_id     = $id;
            $objUserCoupon->coupon_id   = $coupon->id;
            $objUserCoupon->active      = true;
            $objUserCoupon->save();
        } catch(Exception $e) {
            return response()->json(['error'=> "Hubo un problema al intentar registrar el cupón, vuelve a intentarlo."], 403);
        }

        return response()->json($coupon, 200);
    }

    /**
     * TAYDER DOCUMENTS
     */
    public function uploadDocument(Request $request) {
        $validacion = $this->validaciones->uploadDocument($request);
        
        if( $validacion  !== true){
            return response()->json(['error'=> $validacion->original], 403);
        }

        $response = $this->servicios->uploadFile($request, "documents");
        $response = $response->getData();
        if($response->codigo == 200 && $response->success) {
            $document = new UserDocument();
            $document->user_id      = $request->user_id;
            $document->name         = $request->name;
            $document->file_name    = $response->nombre;
            $document->file_url     = $response->url;
            $document->save();
        } else {
            return response()->json(['error'=> 'No se pudo almacenar el archivo enviado.'], 403);
        }

        return response()->json(['message'=> 'Documento almacenado exitosamente.'], 200);
    }
}
