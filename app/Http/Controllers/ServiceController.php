<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ServiceValidator;
use App\Service;
use App\GeneralSetting;

class ServiceController extends Controller
{
    public $successStatus = 200;
    protected $validations;

    public function __construct(ServiceValidator $validations) {
        $this->validations = $validations;
    }

    public function store(Request $request) {
        $validate = $this->validations->store($request);

        if($validate  !== true) {
            return response()->json(['error'=> $validate->original], 403);
        }

        $services = Service::where('request_user_id', $request->user_id)
                        ->where('service_status_id', 1)
                        ->get();

        if(sizeof($services) > 0) {
            return response()->json(['error'=> 'Existen servicios aún con estatus PENDIENTE, debe cancelar o esperar a que los acepten.'], 403);
        }

        try {
            $serviceSetting = GeneralSetting::where('key', 'SERVICIO_DEPARTAMENTO')->where('active', true)->first();
            $taxPercent     = GeneralSetting::where('key', 'IVA_PORCENTAJE')->where('active', true)->first();
            $taydPercent    = GeneralSetting::where('key', 'TAYD_COMISION')->where('active', true)->first();
            $stripePercent  = GeneralSetting::where('key', 'STRIPE_COMISION_PORCENTAJE')->where('active', true)->first();
            $stripeExtra    = GeneralSetting::where('key', 'STRIPE_COMISION_EXTRA')->where('active', true)->first();
    
            // pre-subtotal del servicio (SERVICIO_BASE + SUBTOTAL) - (DESCUENTO_RECAMARA + DESCUENTO BAÑO)
            $serviceTotal    = $request->service_cost;
            if($request->discount > 0) {
                $serviceTotal = $serviceTotal - $request->discount;
            }
    
            // Impuesto aplicado al pre-subtotal
            $taxService      = $serviceTotal * ($taxPercent->value / 100);
    
            // Comisión obtenida por Tayd
            $taydCommission  = ($serviceTotal + $taxService) * ($taydPercent->value / 100);
    
            // Comisión obtenida por Stripe
            $stripeCommission = (($serviceTotal + $taxService + $taydCommission) * ($stripePercent->value / 100)) + $stripeExtra->value;
    
            // Impuesto aplicado a la comisión de Stripe
            $taxStripe       = $stripeCommission * ($taxPercent->value / 100);
    
            // Total del proceso
            $total          = $serviceTotal + $taxService + $taydCommission + $stripeCommission + $taxStripe;

            $service        = new Service();
            $service->request_user_id   = $request->user_id;
            $service->user_property_id  = $request->user_property_id;
            $service->stripe_customer_source_id = $request->stripe_customer_source_id;
            $service->service_status_id = 1;
            $service->dt_request        = $request->date." ".$request->time;
            $service->has_consumables   = $request->has_consumables;
            $service->service_cost      = $serviceTotal;
            $service->tax_service       = $taxService;
            $service->tayd_commission   = $taydCommission;
            $service->stripe_commission = $stripeCommission;
            $service->tax_stripe        = $taxStripe;
            $service->discount          = $request->discount;
            $service->total             = $total;
            $service->save();

        } catch(Exception $exception) {
            return response()->json(['error'=> 'Ocurrió un error al realizar la solicitud del servicio.'], 403);
        }

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

    public function listScheduled($userId) {
        $services = Service::where('request_user_id', $userId)
                        ->whereIn('service_status_id', [1, 2, 3])
                        ->get();

        return response()->json($services, 200);
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
