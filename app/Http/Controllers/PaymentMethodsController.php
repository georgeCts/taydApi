<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserStripeCustomer;
use App\StripeCustomerSource;
use Stripe;
use Exception;
use DB;

class PaymentMethodsController extends Controller
{
    public function listCards($id) {
        $arrSources = array();
        $customer   = UserStripeCustomer::where('user_id', $id)->first();

        if(is_null($customer)){
            return response()->json(['error'=> "No existe un token asignado a este usuario."], 403);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        foreach($customer->customerSources as $source) {
            $result = \Stripe\Customer::retrieveSource($customer->stripe_customer_token, $source->stripe_customer_source_token, []);
            array_push($arrSources, array(
                "id"                => $source->id,
                "key"               => $result->id,
                "brand"             => $result->brand,
                "name"              => $result->name,
                "exp_month"         => $result->exp_month,
                "exp_year"          => $result->exp_year,
                "number"            => "**** **** **** " . $result->last4,
                "country"           => $result->country,
                "is_predetermined"  => $source->is_predetermined
            ));
        }

        return response()->json($arrSources, 200);

    }

    public function store(Request $request) {
        $customer = UserStripeCustomer::where('user_id', $request->user_id)->first();

        if(is_null($customer)){
            return response()->json(['error'=> "No existe un token asignado a este usuario."], 403);
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $source = \Stripe\Customer::createSource($customer->stripe_customer_token, [
                'source' => $request->token
            ]);

            $objSource                                  = new StripeCustomerSource();
            $objSource->user_stripe_customer_id         = $customer->id;
            $objSource->stripe_customer_source_token    = $source->id;
            $objSource->save();

        } catch(\Stripe\Exception\ApiErrorException $e) {
            return response()->json( ['error'=> $e->getError()->message], 403);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json( ['error'=> 'Ocurrió un problema al conectar con Stripe.'], 403);
        } catch (Exception $e) {
            return response()->json( ['error'=> 'Ocurrió un error al realizar el proceso.'], 403);
        }

        return response()->json($objSource, 200);
    }

    public function getPredetermined($id) {
        $customer   = UserStripeCustomer::where('user_id', $id)->first();
        
        if(is_null($customer)){
            return response()->json(['error'=> "No existe un token asignado a este usuario."], 403);
        }
        
        $source = StripeCustomerSource::where('user_stripe_customer_id', $customer->id)
                    ->where('is_predetermined', true)
                    ->first();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $result     = \Stripe\Customer::retrieveSource($customer->stripe_customer_token, $source->stripe_customer_source_token, []);
        $response   = array(
            "id"                => $source->id,
            "key"               => $result->id,
            "brand"             => $result->brand,
            "name"              => $result->name,
            "exp_month"         => $result->exp_month,
            "exp_year"          => $result->exp_year,
            "number"            => "**** **** **** " . $result->last4,
            "country"           => $result->country,
            "is_predetermined"  => $source->is_predetermined
        );

        return response()->json($response, 200);

    }

    public function setPredetermined($id) {
        $source = StripeCustomerSource::find($id);
        if(is_null($source)){
            return response()->json( ['error'=> "No se encontro la tarjeta bancaria con id ".$id], 403);
        }

        $source->is_predetermined = true;
        $source->save();

        DB::table('stripe_customers_sources')
                        ->where('id', '!=', $id)
                        ->where('user_stripe_customer_id', $source->user_stripe_customer_id)
                        ->update(['is_predetermined' => false]);

        return response()->json(['message' => 'Operación exitosa.'], 200);
    }
}
