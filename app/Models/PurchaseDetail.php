<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';
    protected $fillable = [
        'purchase_id',
        'article_id',
        'category_id',
        'brand_id',
        'price',
        'quantity',
        'subtotal',
        'tax',
        'total'
    ];

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }

    public function article(){
        return $this->belongsTo(Article::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
}
