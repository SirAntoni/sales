<?php

namespace App\Livewire\Documents;

use App\Models\Article;
use App\Models\Contact;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetail;
use Livewire\Component;

class NewDocument extends Component
{
    public $id;
    public $clients;
    public $client;
    public $defaultClient;
    public $defaultPaymentMethod;
    public $defaultContact;
    public $articles;
    public $contacts;
    public $tax;
    public $number;
    public $contact;
    public $paymentMethod;
    public $paymentMethods;
    public $delivery_fee;
    public $date;
    public $granSubtotal;
    public $granTax;
    public $granTotal;
    public $dateSelected;
    public $articleSelected;
    public $articlesSelected = [];

    public $clientSelected;

    public function mount(){
        $sale = Sale::find($this->id);


        $contacts = Contact::select('id','name')->get();
        $paymentMethods = PaymentMethod::select('id','name')->get();

        //Inicio Client
        $this->client = $sale->client_id;
        $this->clientSelected = $sale->client;
        //Fin Client

        $this->contact = $sale->contact_id;
        $this->paymentMethod = $sale->payment_method_id;;
        $this->contacts = $contacts;
        $this->paymentMethods = $paymentMethods;
        $this->date = $sale->date;
        $this->defaultClient = $sale->client->id;
        $this->defaultPaymentMethod = $sale->paymentMethod->id;
        $this->defaultContact = $sale->contact->id;
        $this->number = $sale->number;
        $this->delivery_fee = $sale->delivery_fee;
        $this->granSubtotal = $sale->granSubtotal;
        $this->tax = ($sale->tax > 0) ? 1:0;

        foreach ($sale->saleDetails as $detail) {
            $this->addToArticleSale($detail->id);
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

                if ($this->articlesSelected[$index]['quantity'] < $article->stock) {
                    $this->articlesSelected[$index]['quantity']++;
                    $this->articlesSelected[$index]['total'] = $this->articlesSelected[$index]['quantity'] * $article->purchase_price;
                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }

            } else {

                if ($article->stock > 0) {

                    $this->articlesSelected[] = [
                        'id' => $article->id,
                        'category' => $article->category_id,
                        'brand' => $article->brand_id,
                        'title' => $article->title,
                        'price' => $article->sale_price,
                        'quantity' => 1,
                        'total' => $article->sale_price
                    ];



                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }
            }

            $this->calculateTotals();
        }
    }

    public function addToArticleSale($id)
    {

        $article = SaleDetail::with('article')->find($id);


        if ($article) {

            $index = collect($this->articlesSelected)->search(function ($item) use ($article) {
                return $item['id'] == $article->id;
            });

            if ($index !== false) {

                if ($this->articlesSelected[$index]['quantity'] < $article->stock) {
                    $this->articlesSelected[$index]['quantity']++;
                    $this->articlesSelected[$index]['total'] = $this->articlesSelected[$index]['quantity'] * $article->purchase_price;
                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }

            } else {

                if ($article->article->stock > 0) {

                    $this->articlesSelected[] = [
                        'id' => $article->article->id,
                        'category' => $article->article->category_id,
                        'brand' => $article->brand_id,
                        'title' => $article->article->title,
                        'price' => $article->price,
                        'quantity' => $article->quantity,
                        'total' => $article->price * $article->quantity,
                    ];



                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }
            }

            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->granSubtotal = collect($this->articlesSelected)->sum('total');
        if ($this->tax == 1) {
            $this->granTotal = $this->granSubtotal + ($this->granSubtotal * 0.18);
            $this->granTax = $this->granSubtotal * 0.18;
        } else {
            $this->granTotal = $this->granSubtotal;
            $this->granTax = 0;
        }
    }
    public function render()
    {
        return view('livewire.documents.new-document');
    }
}
