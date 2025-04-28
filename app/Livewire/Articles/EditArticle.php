<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

class EditArticle extends Component
{
    public $id;
    public $title = '';
    public $detail = '';
    public $description = '';
    public $sku;
    public $stock;
    public $brand_id = '';
    public $category_id = '';
    public $purchase_price;
    public $sale_price;

    public function mount(){
        $article = Article::find($this->id);
        $this->title = $article->title;
        $this->detail = $article->detail;
        $this->description = $article->description;
        $this->sku = $article->sku;
        $this->stock = $article->stock;
        $this->brand_id = $article->brand_id;
        $this->category_id = $article->category_id;
        $this->purchase_price = sprintf('%.2f', $article->purchase_price);
        $this->sale_price = sprintf('%.2f', $article->sale_price);
    }

    protected $rules = [
        'title' => 'required|min:3',
        'detail' => 'string|nullable|max:250',
        'description' => 'string|nullable|max:500',
        'sku' => 'required|string|min:3',
        'stock' => 'integer|nullable',
        'brand_id' => 'required|integer|exists:brands,id',
        'category_id' => 'required|integer|exists:categories,id',
        'purchase_price' => 'decimal:2|nullable',
        'sale_price' => 'decimal:2|nullable',
    ];

    protected $validationAttributes = [
        'title' => 'titulo',
        'detail' => 'detalle',
        'description' => 'descripción',
        'brand_id' => 'marca',
        'category_id' => 'categoría',
        'purchase_price' => 'precio de compra',
        'sale_price' => 'precio de venta',
    ];

    public function save(){
        $this->validate();
        $article = Article::find($this->id)->update([
            'title' => $this->title,
            'detail' => $this->detail,
            'description' => $this->description,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'purchase_price' => $this->purchase_price,
            'sale_price' => $this->sale_price
        ]);
        $this->dispatch('success',['label' => 'Se edito el artículo con éxito.','btn' => 'Ir a artículos','route' => route('articles.index')]);
    }


    public function render()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('livewire.articles.edit-article',compact('categories','brands'));
    }
}
