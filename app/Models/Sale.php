<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'date', 'invoice_total', 'invoice_number','bom_sale_id'];
    public function saleItems(){
        return $this->hasMany(SaleItem::class);
    }
}
