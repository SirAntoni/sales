<?php

namespace App\Livewire\Documents;

use Livewire\Component;

class NewDocument extends Component
{
    public $contacts;
    public $paymentMethods;
    public $granSubtotal;
    public $granTax;
    public $granTotal;

    public function mount(){
        $this->contacts = \App\Models\Contact::all();
        $this->paymentMethods = \App\Models\PaymentMethod::all();
    }
    public function render()
    {
        return view('livewire.documents.new-document');
    }
}
