<?php

namespace App\Livewire\Purchases;

use App\Models\Article;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Livewire\Component;
use App\Models\Provider;
use Log;

class ShowPurchase extends Component
{
    public $id;
    public $provider;
    public $voucher_type;
    public $document;
    public $passenger;
    public $granSubtotal = 0;
    public $granTotal = 0;
    public $granTax = 0;
    public $articlesSelected = [];
    public $number;

    public function mount(){

        $purchase = Purchase::find($this->id);
        $this->number = $purchase->number;
        $this->provider = $purchase->provider->name;
        $this->voucher_type = $purchase->voucher_type;
        $this->document = $purchase->document;
        $this->passenger = $purchase->passenger;
        $this->granSubtotal = $purchase->subtotal;
        $this->granTax = $purchase->tax;
        $this->granTotal = $purchase->total;

        $this->articlesSelected = PurchaseDetail::where('purchase_id', $this->id)->get();

    }
    public function render()
    {
        return view('livewire.purchases.show-purchase');
    }
}
