<?php

namespace App\Livewire\PaymentMethods;

use App\Models\PaymentMethod;
use Livewire\Component;

class EditPaymentMethod extends Component
{
    public $id;
    public $name;

    protected $rules = [
        'name'   => 'required'
    ];
    public function mount(){
        $paymentMetod = PaymentMethod::find($this->id);
        $this->name = $paymentMetod->name;
    }
    public function save(){
        $this->validate();
        PaymentMethod::find($this->id)->update(['name' => $this->name]);
        $this->dispatch('success',['label' => 'Se edito el método de pago con éxito.','btn' => 'Ir a métodos de pago','route' => route('payment-methods.index')]);
    }
    public function render()
    {
        return view('livewire.payment-methods.edit-payment-method');
    }
}
