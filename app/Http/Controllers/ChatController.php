<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\ChatValidator;
use App\ChatMessage;
use Carbon\Carbon;
use Pusher\Pusher;

class ChatController extends Controller
{
    public $successStatus = 200;
    private $pusher;
    protected $validaciones;

    public function __construct(ChatValidator $validaciones) {
        $this->validaciones = $validaciones;

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

    public function storeMessage(Request $request) {
        $validacion = $this->validaciones->store($request);
        $data       = $request->all();

        if($validacion  !== true) {
            return response()->json(['error'=> $validacion->original], 403);
        }

        if(isset($data["id"]) || is_null($request->id)) {
            unset($data['id']);
        }

        $message                = ChatMessage::create($data);
        $message->user_name     = $message->service->requester->info->name;
        $message->provider_name = $message->service->provider->info->name;

        $this->pusher->trigger('chat'.$message->service_id, 'new-message', ["message" => $message]);

        return response()->json(['message'=> $message], 200);
    }

    public function getMessages($serviceId) {
        $response = array();
        $messages = ChatMessage::where('service_id', $serviceId)->get();

        foreach($messages as $message) {
            array_push($response, array(
                "id"            => $message->id,
                "service_id"    => $message->service_id,
                "user_name"     => $message->service->requester->info->name,
                "provider_name" => $message->service->provider->info->name,
                "message"       => $message->message,
                "fromTayder"    => $message->fromTayder,
                "created_at"    => Carbon::parse($message->created_at)->format("Y-m-d H:i:s")
            ));
        }

        return response()->json($response, 200);
    }
}
