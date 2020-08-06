<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneralSetting;

class GeneralSettingsController extends Controller
{
    public function listAll() {
        $arrSettings = GeneralSetting::where('active', true)->get();

        if(is_null($arrSettings) || sizeof($arrSettings) < 1){
            return response()->json( ['error'=> "No se encontraron configuraciones generales activas."], 403);
        }

        return response()->json($arrSettings, 200);
    }

    public function getByKey($key) {
        $setting = GeneralSetting::where('key', $key)
                        ->where('active', true)
                        ->first();

        if(is_null($setting)){
            return response()->json( ['error'=> "No se encontró la configuración con la key: ".$key], 403);
        }

        return response()->json($setting, 200);
    }
}
