<?php

namespace App\Livewire\Vouchers;

use App\Models\Voucher;
use Livewire\Component;

class EditVoucher extends Component
{
    public $id;
    public $name;
    public $serie;
    public $number;

    protected $rules = [
        'name' => 'required|string|min:3',
        'serie' => 'required|min:1',
        'number' => 'required|numeric|min:1',
    ];

    public function mount(){
        $voucher = Voucher::find($this->id);
        $this->name = $voucher->name;
        $this->serie = $voucher->serie;
        $this->number = $voucher->number;
    }

    public function save(){

        $this->validate();

        Voucher::find($this->id)->update([
            'name' => $this->name,
            'serie' => $this->serie,
            'number' => $this->number
        ]);

        $this->dispatch('success',['label' => 'Se edito el comprobante con éxito.','btn' => 'Ir a comprobante','route' => route('vouchers.index')]);
    }

    public function render()
    {
        return view('livewire.vouchers.edit-voucher');
    }
}
