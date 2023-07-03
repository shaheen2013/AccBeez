<?php

namespace App\Http\Controllers;

use App\Imports\BillsImport;
use App\Imports\BomsImport;
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
}
