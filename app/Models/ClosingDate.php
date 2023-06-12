<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingDate extends Model
{
    use HasFactory;
    protected $fillable = ['sku', 'date', 'status'];
    public function billItem(){
        return $this->belongsTo(BillItem::class, 'sku', 'sku');
    }
}
