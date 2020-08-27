<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChatValidator;
use App\ChatMessage;

class ChatController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;

    public function __construct(ChatValidator $validaciones) {
        $this->validaciones = $validaciones;
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

        return response()->json(['message'=> $message], 200);
    }

    public function getMessages($serviceId) {
        $messages = ChatMessage::where('service_id', $serviceId)->get();

        return response()->json($messages, 200);
    }
}
