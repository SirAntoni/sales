<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'document_number',
        'document_type',
        'address',
        'phone',
        'email',
        'department_id',
        'province_id',
        'district_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }
}
