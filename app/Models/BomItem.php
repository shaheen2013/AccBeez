<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    use HasFactory;
    protected $fillable = ['bom_id', 'quantity', 'rate', 'total', 'sku', 'company_id'];
    public function bom(){
        return $this->belongsTo(Bom::class);
    }
}
