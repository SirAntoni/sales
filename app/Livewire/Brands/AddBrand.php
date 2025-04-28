<?php

namespace App\Livewire\Brands;

use App\Models\Brand;
use Livewire\Component;

class AddBrand extends Component
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
        foreach ($this->names as $brandName) {
            Brand::create([
                'name' => $brandName,
            ]);
        }

        $this->dispatch('success',['label' => 'Se agrego la(s) marca(s) con éxito.','btn' => 'Ir a marcas','route' => route('brands.index')]);
    }
    public function render()
    {
        return view('livewire.brands.add-brand');
    }
}
