<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomSaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['bom_sale_id', 'bom_id', 'quantity', 'rate', 'total', 'Sku', 'name', 'unit', 'client_id'];
    public function bomSale(){
        return $this->belongsTo(BomSale::class);
    }
    public function bom(){
        return $this->belongsTo(Bom::class);
    }

    public function saleItems(){
        return $this->hasMany(SaleItem::class,'bom_sale_item_id','id');
    }
}
