<?php

namespace App\Imports;

use App\Models\Bom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BomsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if($key != 0) {
                Bom::create([
                    'name' => $row[1],
                    'invoice_total' => $row[2]
                ]);
            }
        }  
    }
}
