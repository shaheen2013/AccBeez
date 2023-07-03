<?php

namespace App\Imports;

use App\Models\Sale;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if($key != 0) {
                Sale::create([
                    'description' => $row[1],
                    'date' => $row[2],
                    'invoice_total' => $row[3],
                    'invoice_number' => $row[4],
                ]);
            }
        }  
    }
}
