<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

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
}
