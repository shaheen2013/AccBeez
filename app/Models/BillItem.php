<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function bill(){
        return $this->belongsTo(Bill::class);
    }
    public function saleItems(){
        return $this->hasMany(SaleItem::class, 'sku', 'sku');
    }
    public function closingDates(){
        return $this->hasMany(ClosingDate::class, 'sku', 'sku');
    }
}
