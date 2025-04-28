<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = ['name'];

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
