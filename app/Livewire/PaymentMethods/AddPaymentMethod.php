<?php

namespace App\Livewire\PaymentMethods;

use App\Models\PaymentMethod;
use Livewire\Component;

class AddPaymentMethod extends Component
{
    public $names = [''];

    protected $rules = [
        'names'   => 'required|array|min:1',
        'names.*' => 'required|string|min:3',
    ];

    protected $messages = [
        'names.*.required'    => 'El nombre es requerido.',
        'names.*.min'    => 'El campo nombre debe tener al menos 3 caracteres.',
    ];

    public function addField()
    {
        $this->names[] = ''; // añadimos un nuevo elemento al array
    }

    public function save(){
        $this->validate();
        foreach ($this->names as $paymentMethodName) {
            PaymentMethod::create([
                'name' => $paymentMethodName,
            ]);
        }
        $this->dispatch('success',['label' => 'Se agrego lo(s) método(s) de pago con éxito.','btn' => 'Ir a métodos de pago','route' => route('payment-methods.index')]);
    }
    public function render()
    {
        return view('livewire.payment-methods.add-payment-method');
    }
}
