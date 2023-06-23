<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'quantity', 'rate', 'total', 'sku', 'name', 'unit', 'client_id','bom_sale_item_id'];
    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
