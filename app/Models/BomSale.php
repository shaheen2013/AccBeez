<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomSale extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'date', 'invoice_total', 'invoice_number'];
    public function bomSaleItems(){
        return $this->hasMany(BomSaleItem::class);
    }
}
