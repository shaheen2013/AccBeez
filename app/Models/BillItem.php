<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $fillable = ['bill_id', 'quantity', 'rate', 'total', 'Sku', 'name', 'unit', 'client_id'];
    public function bill(){
        return $this->belongsTo(Bill::class);
    }
    public function saleItems(){
        return $this->hasMany(SaleItem::class, 'Sku', 'Sku');
    }
    public function closingDates(){
        return $this->hasMany(ClosingDate::class, 'Sku', 'Sku');
    }
}
