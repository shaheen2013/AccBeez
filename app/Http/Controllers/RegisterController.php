<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = BillItem::all();
        return response()->json($registers);
    }
}
