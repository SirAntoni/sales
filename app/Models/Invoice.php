<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    protected $fillable = [
        'sale_id',
        'serie',
        'numero',
        'xml_path',
        'cdr_path',
        'status_sunat',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
