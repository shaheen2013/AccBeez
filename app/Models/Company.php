<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    protected $appends = ['remaining_time'];

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

    public function getRemainingTimeAttribute()
    {
        if ($this->deleted_at) {
            $currentTime = Carbon::parse(now());
            $deletedTime = Carbon::parse($this->deleted_at);
            $deletedTime = $deletedTime->addDays(30);
            $remaining = $currentTime->diff($deletedTime);

            return $remaining->days;
        }

        return null; // Return null if deleted_at is not set
    }
}
