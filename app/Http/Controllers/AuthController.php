<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserValidator;
use Twilio\Rest\Client;
use App\User;
use App\UserInfo;
use App\UserStripeCustomer;
use Carbon\Carbon;
use Exception;
use Auth;
use Stripe;

class AuthController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;
    protected $sid;
    protected $token;

    public function __construct(UserValidator $validaciones) {
        $this->validaciones = $validaciones;
        $this->sid          = getenv("TWILIO_ACCOUNT_SID");
        $this->token        = getenv("TWILIO_AUTH_TOKEN");
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $user->info;

            $tokenResult =  $user->createToken('Personal Access Token');
            $token       = $tokenResult->token;
            $token->expires_at = Carbon::now()->addDays(1);

            if($request->remember_me)
                $token->expires_at = Carbon::now()->addDays(1);

            $token->save();

            return response()->json([
                'access_token'  => $tokenResult->accessToken,
                'user'          => $user,
                'token_type'    => 'Bearer',
                'expires_at'    => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ], $this->successStatus);
        }
        else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function signup(Request $request) {
        $validacion = $this->validaciones->signup($request);

        if($validacion  !== true)
            return response()->json(['error'=> $validacion->original], 403);

        try {
            $user               = new User();
            $user->email        = $request->email;
            $user->password     = bcrypt($request->password);
            $user->confirmed    = false;
            $user->first_login  = true;
            $user->isTayder     = $request->isTayder;
            $user->save();
    
            if($user != null) {
                $info               = new UserInfo();
                $info->user_id      = $user->id;
                $info->name         = $request->name;
                $info->last_name    = $request->last_name;
                $info->phone        = $request->phone;
                $info->photo        = "/storage/photos/default.jpg";
                $info->save();
            }
    
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $customer   = \Stripe\Customer::create([
                'description'   => 'Customer Tayd App',
                'email'         => $user->email,
                'phone'         => $request->phone,
                'name'          => $request->name . " " . $request->last_name,
            ]);
    
            if(!is_null($customer)) {
                $objCustomer                        = new UserStripeCustomer();
                $objCustomer->user_id               = $user->id;
                $objCustomer->stripe_customer_token = $customer->id;
                $objCustomer->save();
            }
        } catch(Exception $e) {
            return response()->json(['error'=> $e], 403);
        }

        return response()->json(['user'=> $user], 200);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => "Successfully logged out"
        ]);
    }

    public function sendVerificationCode(Request $request) {
        try {
            $twilio = new Client($this->sid, $this->token);

            $verification = $twilio->verify->v2
                                ->services("VAbae211d6cdcd683f14138eb5317413e2")
                                ->verifications->create("+52".$request->phone, "sms");
        } catch(Exception $ex) {
            return response()->json(['error'=> "No fue posible enviar el código de verificación a este número telefónico."], 403);
        }

        return response()->json(['message' => "Operación exitosa"], 200);
    }

    public function confirmVerificationCode(Request $request) {
        $validacion = $this->validaciones->verificationCode($request);

        if($validacion  !== true)
            return response()->json(['error'=> $validacion->original], 403);

        try {
            $twilio = new Client($this->sid, $this->token);

            $verification_check = $twilio->verify->v2
                                ->services("VAbae211d6cdcd683f14138eb5317413e2")
                                ->verificationChecks
                                ->create($request->code,
                                        ["to" => "+52".$request->phone]
                                    );
        } catch(Exception $ex) {
            return response()->json(['error'=> "El código ingresado es inválido o expiró."], 403);
        }

        return response()->json(['message' => "Operación exitosa"], 200);
    }
}
