<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserValidator;
use App\Services\FileService;
use App\UserDocument;
use App\User;

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
        if(is_null($user)){
            return response()->json( ['error'=> "No se encontro el usuario con id ".$id], 403);
        }
        $user->info;
        $user->documents;

        return response()->json($user, 200);
    }

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
