<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function Functional\true;
use Exception;

class FileService 
{
    public function uploadFile(Request $request, $type, $update = false) {
        $folder = "U".$request->user_id;
        if($type == "photo"){
            if($update && $request->photo != "/storage//fotos/default.jpg"){
                $anterior = explode("//", $request->photo);
                $original = storage_path()."/app/public/".$anterior[1];
                File::delete($original);
            }
            $path = storage_path().'/app/public/'.$type.'/' .$folder;
        }
        else{
            $path = storage_path().'/'.$type.'/'.$folder;
        }
        
        if($this->createFolder($path)){
            if($request->hasFile('file')){
                $archivo = $request->file;
                $response = $this->upload($request, $folder, $path, $type);
            }else{
                $response['message'] = 'No es un archivo';
                $response['codigo'] = 403;
            }
        }
        
        return response()->json($response, $response["codigo"]);
    }

    private function createFolder($path){
        $response = true;
        if(!File::isDirectory($path)){
            if(!File::makeDirectory($path, $mode = 0777, true, true)){
                $response = false;
            }
        }
        return $response;
    }

    private function upload($request, $destiny, $path, $type){
        $response = array(
            "nombre" => "",
            "extension" => "",
            "url" => "",
            "success" => false,
            "codigo" => 403,
            "message" => ""
        );
        
        /* if(!is_null($request->documento_id) && $request->documento_id != 0){
            try{
                $original = DB::table('documentos')->where("id", "=", $request->documento_id)->get();
                $archivoOriginal = storage_path().$original[0]->url;
                File::delete($archivoOriginal);
            }
            catch(Exception $ex){
                $response = array(
                    "message" => "No se encontro el archivo anterior.",
                    "url" => $archivoOriginal,
                    "success" => false,
                    "codigo" => 403
                );
                return $response;
            }
        } */

        $file       = $request->file;
        $extension  = $file->getClientOriginalExtension();
        $fileName   = time().'_document.'.$extension;
        $url        = '/'.$type.'/' .$destiny.'/'.$fileName;

        try{
            if($file->move($path, $fileName)){
                $response = array(
                    "nombre"    => $fileName,
                    "extension" => $extension,
                    "url"       => $url,
                    "success"   => true,
                    "codigo"    => 200
                );
            }
        }
        catch(Exception $ex){
            $response["message"] = $ex;
        }
        
        return $response;
    }

    /* public function documentoDelete($id){
        $response = array('message' => 'Error');
        $codigo = 403;
        
        try{
            $original = DB::table('documentos')->where("id", "=", $id)->get();
            $archivoOriginal = storage_path().$original[0]->url;
            File::delete($archivoOriginal);
            $response["message"] = "Operacion Exitosa.";
            $codigo = 200;
        }
        catch(Exception $ex){
            $response["message"] = "No se encontro el archivo";
        }
        
        return response()->json($response, $codigo);
    } */
}
