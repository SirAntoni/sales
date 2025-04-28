<?php

namespace App\Livewire\Vouchers;

use Livewire\Component;
use App\Models\Voucher;

class AddVoucher extends Component
{
    public $name;
    public $serie;
    public $number;

    protected $rules = [
        'name' => 'required|string|min:3',
        'serie' => 'required|numeric|min:1',
        'number' => 'required|numeric|min:1',
    ];

    public function render()
    {
        return view('livewire.vouchers.add-voucher');
    }

    public function save(){
        $this->validate();

        $user = Voucher::create([
            'name' => $this->name,
            'serie' => $this->serie,
            'number' => $this->number
        ]);

        $this->dispatch('success',['label' => 'Se agrego el comprobante con éxito.','btn' => 'Ir a comprobantes','route' => route('vouchers.index')]);
    }
}
