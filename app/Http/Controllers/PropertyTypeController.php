<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PropertyType;

class PropertyTypeController extends Controller
{
    public function getAll() {
        $result = PropertyType::where('active', true)->get();

        return response()->json(['propertyTypes'=> $result], 200);
    }
}
