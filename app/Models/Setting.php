<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company',
        'ruc',
        'country',
        'address',
        'city',
        'phone',
        'email',
        'exchange_rate'
    ];
}
