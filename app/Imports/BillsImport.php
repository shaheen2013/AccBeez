<?php

namespace App\Imports;

use App\Models\Bill;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class BillsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if($key != 0) {
                Bill::create([
                    'date' => $row[0],
                    'description' => $row[1],
                    'invoice_total' => $row[2]
                ]);
            }
        }   
    }
}
