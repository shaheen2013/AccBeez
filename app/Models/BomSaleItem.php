<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomSaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['bom_sale_id', 'bom_id', 'quantity', 'rate', 'total', 'sku', 'name', 'unit', 'client_id'];
    public function bomSale(){
        return $this->belongsTo(BomSale::class);
    }
    public function bom(){
        return $this->belongsTo(Bom::class);
    }
}
