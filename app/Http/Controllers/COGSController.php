<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use Illuminate\Http\Request;

class COGSController extends Controller
{
    public function getAll(){
        $sales = 
        $boms = Bom::with('bomItems')->get();
        return response()->json($boms);
    }
}
