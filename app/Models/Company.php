<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function bills() 
    {
        return $this->hasMany(Bill::class);
    }
    public function boms() 
    {
        return $this->hasMany(Bom::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function bomSales()
    {
        return $this->hasMany(BomSale::class);
    }
    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class);
    }

    // public function timeRemaining(){
    //     $deletedTime = date("Y-m-d H:i:s",strtotime($this->deleted_at));
    //     $currentTime = date("Y-m-d H:i:s",strtotime(now()));
    //     $remaining = $currentTime - $deletedTime;
    //     $remDays = date("d",strtotime($remaining));
    //     return $remDays;
    // }
}
