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
}
