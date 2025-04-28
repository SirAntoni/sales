<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
