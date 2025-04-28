<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'document_number',
        'document_type',
        'address',
        'phone',
        'email'
    ];

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }
}
