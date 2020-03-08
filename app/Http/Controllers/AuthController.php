<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserValidator;
use App\User;
use App\UserInfo;
use Carbon\Carbon;
use Exception;
use Auth;

class AuthController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;

    public function __construct(UserValidator $validaciones) {
        $this->validaciones = $validaciones;
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $user->info;

            $tokenResult =  $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addDays(1);

            if ($request->remember_me)
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

        if($validacion  !== true){
            return response()->json(['error'=> $validacion->original], 403);
        }

        $user               = new User();
        $user->email        = $request->email;
        $user->password     = bcrypt($request->password);
        $user->confirmed    = false;
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

        return response()->json(['user'=> $user], 200);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => "Successfully logged out"
        ]);
    }
}
