<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\ChatValidator;
use Pusher\Pusher;
use App\ChatMessage;

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

        $message = ChatMessage::create($data);

        $this->pusher->trigger('chat'.$message->service_id, 'new-message', ["message" => $message]);

        return response()->json(['message'=> $message], 200);
    }

    public function getMessages($serviceId) {
        $messages = ChatMessage::where('service_id', $serviceId)->get();

        return response()->json($messages, 200);
    }
}
