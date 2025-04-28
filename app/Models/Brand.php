<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public  function articles(){
        return $this->hasMany(Article::class);
    }

    public function purchaseDetail(){
        return $this->hasMany(PurchaseDetail::class);
    }

    public function saleDetail(){
        return $this->hasMany(SaleDetail::class);
    }
}
