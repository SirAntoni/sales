<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;
use Livewire\Features\SupportAttributes\AttributeCollection;

class AddArticle extends Component
{
    public $title = '';
    public $detail = '';
    public $description = '';
    public $sku;
    public $stock;
    public $brand_id = '';
    public $category_id = '';
    public $purchase_price;
    public $sale_price;

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


    public function save()
    {
        $this->validate();

        Article::create([
            'title' => $this->title,
            'detail' => $this->detail ?? '',
            'description' => $this->description ?? '',
            'sku' => $this->sku,
            'stock' => $this->stock ?? 0,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'purchase_price' => $this->purchase_price ?? 0,
            'sale_price' => $this->sale_price ?? 0
        ]);

        $this->dispatch('success', ['label' => 'El artículo se agregó con éxito.', 'btn' => 'Ir a articulos', 'route' => route('articles.index')]);
    }

    public function render()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('livewire.articles.add-article', compact('categories', 'brands'));
    }
}
