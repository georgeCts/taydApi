<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\ServiceValidator;
use App\Events\ServiceCreatedEvent;
use Carbon\Carbon;
use App\Service;
use App\GeneralSetting;
use Stripe\Stripe;
use Stripe\Charge;
use Pusher\Pusher;
use DB;

class ServiceController extends Controller
{
    private $pusher;
    public $successStatus = 200;
    public $stripeSuccess = "succeeded";
    protected $validations;

    public function __construct(ServiceValidator $validations) {
        $this->validations = $validations;

        $config = Config::get('broadcasting.connections.pusher');

        $options = [
            'cluster'   => $config['options']['cluster'],
            'encrypted' => $config['options']['encrypted']
        ];

        $this->pusher = new Pusher(
            $config['key'],
            $config['secret'],
            $config['app_id'],
            $options
        );
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
            DB::beginTransaction();

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

            $service                    = new Service();
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

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = \Stripe\Charge::create([
                'amount'        => round($service->total, 0, PHP_ROUND_HALF_UP) * 100,
                'currency'      => 'mxn',
                'customer'      => $service->requester->stripeCustomer->stripe_customer_token,
                'source'        => $service->stripeSource->stripe_customer_source_token,
                'description'   => 'Servicio de limpieza TAYD.',
                'capture'       => false
            ]);

            if($charge->status == $this->stripeSuccess) {
                $service->charge_token  = $charge->id;
                $service->save();

                $service->property;

                DB::commit();

                $this->pusher->trigger('private-notifications', 'service-accepted', $service);
                //event(new ServiceCreatedEvent($service));
            } else {
                DB::rollBack();
                return response()->json(['error'=> 'No fue posible realizar el cobro a la tarjeta seleccionada.'], 403);
            }

        } catch(Exception $exception) {
            DB::rollBack();
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

    public function getEarnings($userId) {
        date_default_timezone_set("America/Mexico_City");

        $monday = date('Y-m-d', strtotime('monday this week')) . " 00:00:01";
        $sunday = date('Y-m-d', strtotime('sunday this week')) . " 23:59:59";

        $subtotal = DB::table('services')
                        ->where('service_status_id', 4)
                        ->where('provider_user_id', $userId)
                        ->where('dt_finish', '>=', $monday)
                        ->where('dt_finish', '<=', $sunday)
                        ->sum('service_cost');

        $services_count = DB::table('services')
                            ->where('service_status_id', 4)
                            ->where('provider_user_id', $userId)
                            ->where('dt_finish', '>=', $monday)
                            ->where('dt_finish', '<=', $sunday)
                            ->count();

        return response()->json(["subtotal" => $subtotal, "count" => $services_count], 200);
    }

    public function listScheduled($userId) {
        $response = array();
        $services = Service::where('request_user_id', $userId)
                        ->whereIn('service_status_id', [1, 2, 3])
                        ->orderBy('dt_request', 'ASC')
                        ->get();

        foreach($services as $service) {
            array_push($response, array(
                "id"                    => $service->id,
                "request_user_id"       => $service->request_user_id,
                "request_user_name"     => $service->requester->info->name. " ".$service->requester->info->last_name,
                "provider_user_id"      => $service->request_user_id,
                "provider_user_name"    => is_null($service->provider_user_name) ? "" : $service->provider->info->name. " ".$service->provider->info->last_name,
                "stripe_customer_source_id" => $service->stripe_customer_source_id,
                "service_status_id"     => $service->service_status_id,
                "service_status_name"   => $service->serviceStatus->name,
                "dt_request"            => $service->dt_request,
                "dt_start"              => $service->dt_start,
                "dt_finish"             => $service->dt_finish,
                "dt_canceled"           => $service->dt_canceled,
                "has_consumables"       => $service->has_consumables,
                "service_cost"          => $service->service_cost,
                "tax_service"           => $service->tax_service,
                "tayd_commission"       => $service->tayd_commission,
                "stripe_commission"     => $service->stripe_commission,
                "tax_stripe"            => $service->tax_stripe,
                "discount"              => $service->discount,
                "total"                 => $service->total,
                "created_at"            => Carbon::parse($service->created_at)->format("Y-m-d H:i:s")
            ));
        }

        return response()->json($response, 200);
    }

    public function listTayderScheduled($userId) {
        $response = array();
        $services = Service::where('provider_user_id', $userId)
                        ->whereIn('service_status_id', [2, 3])
                        ->orderBy('dt_request', 'ASC')
                        ->get();

        foreach($services as $service) {
            $arrDistribution = array();

            foreach($service->property->userPropertyDistribution as $distribution) {
                array_push($arrDistribution, array(
                    "user_property_distribution_id"     => $distribution->id,
                    "property_type_price_id"            => $distribution->property_type_price_id,
                    "quantity"                          => $distribution->quantity,
                    "key"                               => $distribution->propertyTypePrice->key,
                    "name"                              => $distribution->propertyTypePrice->name,
                    "price"                             => $distribution->propertyTypePrice->price,
                ));
            }

            array_push($response, array(
                "id"                    => $service->id,
                "request_user_id"       => $service->request_user_id,
                "request_user_name"     => $service->requester->info->name. " ".$service->requester->info->last_name,
                "provider_user_id"      => $service->request_user_id,
                "provider_user_name"    => is_null($service->provider_user_name) ? "" : $service->provider->info->name. " ".$service->provider->info->last_name,
                "property_name"         => $service->property->name,
                "property_latitude"     => $service->property->latitude,
                "property_altitude"     => $service->property->altitude,
                "property_type_id"      => $service->property->propertyType->id,
                "property_type_name"    => $service->property->propertyType->name,
                "distribution"          => $arrDistribution,
                "service_status_id"     => $service->service_status_id,
                "service_status_name"   => $service->serviceStatus->name,
                "dt_request"            => $service->dt_request,
                "has_consumables"       => $service->has_consumables,
                "created_at"            => Carbon::parse($service->created_at)->format("Y-m-d H:i:s")
            ));
        }

        return response()->json($response, 200);
    }

    public function listHistory($userId) {
        $response = array();
        $services = Service::where('request_user_id', $userId)
                        ->whereIn('service_status_id', [4, 5])
                        ->orderBy('id', 'DESC')
                        ->get();

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        for($i = 0; $i < sizeof($services); $i++) {
            $service            = $services[$i];
            $arrDistribution    = array();
            $source             = \Stripe\Customer::retrieveSource($service->requester->stripeCustomer->stripe_customer_token, $service->stripeSource->stripe_customer_source_token, []);

            foreach($service->property->userPropertyDistribution as $distribution) {
                array_push($arrDistribution, array(
                    "user_property_distribution_id"     => $distribution->id,
                    "property_type_price_id"            => $distribution->property_type_price_id,
                    "quantity"                          => $distribution->quantity,
                    "key"                               => $distribution->propertyTypePrice->key,
                    "name"                              => $distribution->propertyTypePrice->name,
                    "price"                             => $distribution->propertyTypePrice->price,
                ));
            }

            array_push($response, array(
                "id"                    => $service->id,
                "request_user_id"       => $service->request_user_id,
                "request_user_name"     => $service->requester->info->name. " ".$service->requester->info->last_name,
                "provider_user_id"      => $service->request_user_id,
                "provider_user_name"    => is_null($service->provider_user_name) ? "" : $service->provider->info->name. " ".$service->provider->info->last_name,
                "property_name"         => $service->property->name,
                "property_type_id"      => $service->property->propertyType->id,
                "property_type_name"    => $service->property->propertyType->name,
                "distribution"          => $arrDistribution,
                "stripe_customer_source_id" => $service->stripe_customer_source_id,
                "stripe_source_brand"   => $source->brand,
                "stripe_source_number"  => "**** **** **** ".$source->last4,
                "stripe_source_name"    => $source->name,
                "service_status_id"     => $service->service_status_id,
                "service_status_name"   => $service->serviceStatus->name,
                "dt_request"            => $service->dt_request,
                "dt_start"              => $service->dt_start,
                "dt_finish"             => $service->dt_finish,
                "dt_canceled"           => $service->dt_canceled,
                "has_consumables"       => $service->has_consumables,
                "service_cost"          => $service->service_cost,
                "tax_service"           => $service->tax_service,
                "tayd_commission"       => $service->tayd_commission,
                "stripe_commission"     => $service->stripe_commission,
                "tax_stripe"            => $service->tax_stripe,
                "discount"              => $service->discount,
                "total"                 => $service->total,
                "rating"                => $service->rating,
                "comments"              => $service->comments,
                "created_at"            => Carbon::parse($service->created_at)->format("Y-m-d H:i:s")
            ));
        }

        return response()->json($response, 200);
    }

    public function listTayderHistory($userId) {
        $services = Service::where('provider_user_id', $userId)
                        ->whereIn('service_status_id', [4, 5])
                        ->orderBy('id', 'DESC')
                        ->get();

        return response()->json($services, 200);
    }

    public function acceptService(Request $request) {
        $response   = array();
        $service    = Service::find($request->service_id);

        if(is_null($service)){
            return response()->json( ['error'=> "No se encontro el servicio con id ".$request->service_id], 403);
        }

        if($service->service_status_id == 1) {
            try {
                DB::beginTransaction();

                $service->service_status_id     = 2;
                $service->provider_user_id      = $request->user_id;
                $service->save();

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $charge = \Stripe\Charge::retrieve($service->charge_token);
                $charge->capture();

                $arrDistribution    = array();

                foreach($service->property->userPropertyDistribution as $distribution) {
                    array_push($arrDistribution, array(
                        "user_property_distribution_id"     => $distribution->id,
                        "property_type_price_id"            => $distribution->property_type_price_id,
                        "quantity"                          => $distribution->quantity,
                        "key"                               => $distribution->propertyTypePrice->key,
                        "name"                              => $distribution->propertyTypePrice->name,
                        "price"                             => $distribution->propertyTypePrice->price,
                    ));
                }

                array_push($response, array(
                    "id"                    => $service->id,
                    "request_user_id"       => $service->request_user_id,
                    "request_user_name"     => $service->requester->info->name. " ".$service->requester->info->last_name,
                    "provider_user_id"      => $service->request_user_id,
                    "provider_user_name"    => is_null($service->provider_user_name) ? "" : $service->provider->info->name. " ".$service->provider->info->last_name,
                    "property_name"         => $service->property->name,
                    "property_latitude"     => $service->property->latitude,
                    "property_altitude"     => $service->property->altitude,
                    "property_type_id"      => $service->property->propertyType->id,
                    "property_type_name"    => $service->property->propertyType->name,
                    "distribution"          => $arrDistribution,
                    "dt_request"            => $service->dt_request,
                    "has_consumables"       => $service->has_consumables,
                    "created_at"            => Carbon::parse($service->created_at)->format("Y-m-d H:i:s")
                ));

                DB::commit();

                $this->pusher->trigger('notifications'.$service->request_user_id, 'service-status', ["message" => "Tu servicio ha sido aceptado por un Tayder."]);
            } catch(Exception $exception) {
                DB::rollBack();
                return response()->json( ['error'=> $exception], 403);
            }
        } else {
            return response()->json( ['error'=> "El servicio ya fue aceptado por otro TAYDER."], 403);
        }

        return response()->json($response, 200);
    }

    public function startService(Request $request) {
        $response    = array();
        $service     = Service::find($request->service_id);
        
        if(is_null($service)) {
            return response()->json( ['error' => "No se encontro el servicio con id ".$request->service_id], 403);
        }
        
        $arrServices    = Service::where('service_status_id', 3)
                            ->where('provider_user_id', $service->provider_user_id)
                            ->get();

        if(sizeof($arrServices) > 0) {
            return response()->json(['error' => 'Actualmente tienes un servicio en curso, intenta de nuevo después de finalizarlo'], 403);
        }

        if($service->service_status_id == 2) {
            try {
                $service->service_status_id     = 3;
                $service->dt_start              = Now();
                $service->save();

                $this->pusher->trigger('notifications'.$service->request_user_id, 'service-status', ["message" => "Tienes 1 cita en curso"]);
            } catch(Exception $exception) {
                return response()->json( ['error'=> $exception], 403);
            }
        } else {
            return response()->json( ['error'=> "El servicio no se encuentra en estatus AGENDADO."], 403);
        }

        return response()->json($service, 200);
    }

    public function finishService(Request $request) {
        $response   = array();
        $service    = Service::find($request->service_id);

        if(is_null($service)){
            return response()->json( ['error'=> "No se encontro el servicio con id ".$request->service_id], 403);
        }

        if($service->service_status_id == 3) {
            try {
                $service->service_status_id     = 4;
                $service->dt_finish             = Now();
                $service->save();

                $this->pusher->trigger('notifications'.$service->request_user_id, 'service-status', ["message" => "Un servicio ha finalizado"]);
            } catch(Exception $exception) {
                return response()->json( ['error'=> $exception], 403);
            }
        } else {
            return response()->json( ['error'=> "El servicio no se encuentra en estatus EN CURSO."], 403);
        }

        return response()->json($service, 200);
    }

    public function rateService(Request $request) {
        $service            = Service::find($request->service_id);

        if(is_null($service)) {
            return response()->json( ['error'=> "No se encontro el servicio con id ".$id], 403);
        }

        $service->rating    = $request->rating;
        $service->comments  = $request->comments;
        $service->save();

        return response()->json(['message' => 'Servicio calificado correctamente.'], 200);
    }

    public function cancel(Request $request) {
        $service = Service::find($request->service_id);

        if(is_null($service)) {
            return response()->json( ['error'=> "No se encontro el servicio con id ".$request->service_id], 403);
        }

        $service->service_status_id = 5;
        $service->dt_canceled       = Now();
        $service->save();

        if($request->from_tayder) {
            $this->pusher->trigger('notifications'.$service->request_user_id, 'service-status', ["message" => "Un servicio ha sido cancelado por un Tayder."]);
        } else {
            if($request->service_status == 2) {
                $this->pusher->trigger('notifications'.$service->provider_user_id, 'service-status', ["message" => "Un servicio ha sido cancelado."]);
            }
        }

        return response()->json(['message' => 'Servicio cancelado correctamente.'], 200);
    }
}
