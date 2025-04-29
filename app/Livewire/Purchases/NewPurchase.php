<?php

namespace App\Livewire\Purchases;

use App\Models\Article;
use App\Models\Purchase;
use Livewire\Component;
use App\Models\Provider;


class NewPurchase extends Component
{
    public $provider;
    public $voucher_type;
    public $article;
    public $document;
    public $passenger;
    public $granSubtotal;
    public $granTotal;
    public $granTax = 0;
    public $tax;
    public $articleSelected;
    public $articlesSelected = [];

    protected $rules = [
        'provider' => 'required',
        'voucher_type' => 'required',
        'document' => 'required',
        'passenger' => 'required',
        'articlesSelected' => 'required|array|min:1',
    ];

    protected $validationAttributes = [
        'provider' => 'proveedor',
        'voucher_type' => 'tipo de documento',
        'document' => 'número de documento',
        'passenger' => 'pasajero'
    ];

    protected $messages = [
        'articlesSelected.required' => 'Debe seleccionar al menos 1 artículo'
    ];

    public function save(){
        $this->validate();
        $purchase = Purchase::create([
            'voucher_type' => $this->voucher_type,
            'document' => $this->document,
            'passenger' => $this->passenger,
            'subtotal'=>$this->granSubtotal,
            'tax' => $this->granTax,
            'total' => $this->granTotal,
            'status'=>1,
            'provider_id'=> $this->provider,
            'user_id'=> auth()->id()
        ]);

        foreach($this->articlesSelected as $article){
            $purchase->purchaseDetails()->create([
                'article_id' => $article['id'],
                'category_id' => $article['category'],
                'brand_id' => $article['brand'],
                'price' => $article['price'],
                'quantity' => $article['quantity'],
                'subtotal' => $article['total'],
                'tax' => ($this->tax == 1) ? $article['total'] * 0.18:0,
                'total' => ($this->tax == 1) ? $article['total'] + ($article['total'] * 0.18): $article['total']
            ]);

            Article::find($article['id'])->increment('stock', $article['quantity']);
            Article::find($article['id'])->update(['provider_id'=> $this->provider]);

        }
        $this->dispatch('success', ['label' => 'La compra fue registrada con éxito.', 'btn' => 'Ir a compras', 'route' => route('purchases.index')]);
    }

    public function updatedArticleSelected($id)
    {

        if ($id) {
            $this->addToArticle($id);
            $this->articleSelected = null;
        }
    }

    public function addToArticle($id)
    {
        $article = Article::find($id);
        if ($article) {
            $index = collect($this->articlesSelected)->search(function ($item) use ($article) {
                return $item['id'] == $article->id;
            });

            if ($index !== false) {
                $this->articlesSelected[$index]['quantity']++;
            } else {
                $this->articlesSelected[] = [
                    'id' => $article->id,
                    'category' => $article->category_id,
                    'brand' => $article->brand_id,
                    'title' => $article->title,
                    'price' => $article->purchase_price,
                    'quantity' => 1,
                    'total'=> $article->purchase_price
                ];
            }

            $this->calculateTotals();

        }
    }

    public function remove($index)
    {
        array_splice($this->articlesSelected, $index, 1);
        $this->calculateTotals();
    }

    public function updateTotal($index)
    {
        if (isset($this->articlesSelected[$index])) {
            $price = (float)$this->articlesSelected[$index]['price'];
            $quantity = (int)$this->articlesSelected[$index]['quantity'];
            $this->articlesSelected[$index]['total'] = $price * $quantity;
            $this->calculateTotals();
        }
    }

    public function calculateTotals(){
        $this->granSubtotal = collect($this->articlesSelected)->sum('total');
        if ($this->tax == 1) {
            $this->granTotal =  $this->granSubtotal + ($this->granSubtotal * 0.18);
            $this->granTax = $this->granSubtotal * 0.18;
        }else{
            $this->granTotal =  $this->granSubtotal;
            $this->granTax = 0;
        }
    }

    public function updateTax(){
        $this->calculateTotals();
    }

    public function searchProviders($query)
    {
        return Provider::query()
            ->where('name', 'like', '%'.$query.'%')
            ->limit(10)
            ->get(['id', 'name'])
            ->map(fn($c) => [
                'value' => $c->id,
                'text'  => $c->name,
            ])
            ->toArray();
    }

    public function searchArticles($query)
    {
        return Article::query()
            ->where('title', 'like', '%'.$query.'%')
            ->limit(10)
            ->get(['id', 'title'])
            ->map(fn($c) => [
                'value' => $c->id,
                'text'  => $c->title,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.purchases.new-purchase');
    }
}
