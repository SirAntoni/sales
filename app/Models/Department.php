<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name'
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function districts()
    {
        return $this->hasManyThrough(District::class, Province::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
