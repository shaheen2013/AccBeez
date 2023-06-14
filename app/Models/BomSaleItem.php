<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomSaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['bom_sale_id', 'quantity', 'rate', 'total', 'sku', 'name', 'unit', 'client_id'];
    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
