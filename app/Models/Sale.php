<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['bom_id', 'amount', 'date'];

    public function bom(){
        return $this->belongsTo(Bom::class, 'bom_id', 'id');
    }
    public function saleItems(){
        return $this->hasMany(SaleItem::class);
    }
}
