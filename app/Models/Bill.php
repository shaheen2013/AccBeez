<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'date', 'invoice_total', 'invoice_number'];
    public function billItems(){
        return $this->hasMany(BillItem::class);
    }
}
