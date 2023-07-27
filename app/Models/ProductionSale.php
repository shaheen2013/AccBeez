<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSale extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function saleItems(){
        return $this->hasMany(ProductionSaleItem::class);
    }
}
