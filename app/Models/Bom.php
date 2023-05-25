<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function bomItems(){
        return $this->hasMany(BomItem::class);
    }
}
