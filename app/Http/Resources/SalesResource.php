<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'description'=>$this->description,
            'date'=>$this->date,
            'invoice_total'=>number_format($this->invoice_total, 2),
            'invoice_number'=>$this->invoice_number,
        ];
    }
}
