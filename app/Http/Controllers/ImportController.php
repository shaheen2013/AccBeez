<?php

namespace App\Http\Controllers;

use App\Imports\BillsImport;
use App\Imports\BomsImport;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function billImport() 
    {
        $file = request()->file('file');

        Excel::import(new BillsImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Bill Imported Successfully!'
        ]);
    }

    public function bomImport()
    {
        $file = request()->file('file');

        Excel::import(new BomsImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Bom Imported Successfully!'
        ]);
    }
    public function saleImport()
    {
        $file = request()->file('file');

        Excel::import(new SalesImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Sale Imported Successfully!'
        ]);
    }
}
