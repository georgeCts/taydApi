<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ServiceValidator;
use App\Service;

class ServiceController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;

    public function __construct(ServiceValidator $validaciones) {
        $this->validaciones = $validaciones;
    }

    public function store(Request $request) {
        $validacion = $this->validaciones->store($request);
        $data       = $request->all();

        if($validacion  !== true) {
            return response()->json(['error'=> $validacion->original], 403);
        }

        if(isset($data["id"]) || is_null($request->id)) {
            unset($data['id']);
        }

        $service = Service::create($data);
        return response()->json(['service'=> $service], 200);
    }

    public function get($id) {
        $service = Service::find($id);
        if(is_null($service)){
            return response()->json( ['error'=> "No se encontro el servicio con id ".$id], 403);
        }
        $service->requester;
        $service->provider;
        $service->property;
        $service->property->propertyType;

        return response()->json($service, 200);
    }

    public function cancel($id) {
        $service = Service::find($id);
        if(is_null($service)){
            return response()->json( ['error'=> "No se encontro el servicio con id ".$id], 403);
        }
        $service->is_canceled   = true;
        $service->dt_canceled   = Now();
        $service->save();

        return response()->json(['message' => 'Servicio cancelado correctamente.'], 200);
    }
}
