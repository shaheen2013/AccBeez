<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $fillable = ['bill_id', 'quantity', 'rate', 'total', 'product_sku'];
    public function bill(){
        return $this->belongsTo(Bill::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
