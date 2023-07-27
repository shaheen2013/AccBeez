<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomSaleItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bomSale(){
        return $this->belongsTo(BomSale::class);
    }
    public function bom(){
        return $this->belongsTo(Bom::class);
    }

    public function saleItems(){
        return $this->hasMany(SaleItem::class,'bom_sale_item_id','id');
    }
    public function closingDates(){
        return $this->hasMany(ClosingDate::class, 'sku', 'sku');
    }
}
