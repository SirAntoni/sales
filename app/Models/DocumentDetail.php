<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    protected $table = 'document_details';
    protected $fillable = [
        'price',
        'quantity',
        'tax',
        'total',
        'document_id',
        'article_id',
        'category_id',
        'brand_id'
    ];
    public function document(){
        return $this->belongsTo(Document::class);
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
