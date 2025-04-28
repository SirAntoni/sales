<?php

namespace App\Livewire\Brands;

use App\Models\Brand;
use Livewire\Component;

class EditBrand extends Component
{
    public $name = '';
    public $id;

    public function mount(){
        $brand = Brand::find($this->id);
        $this->name = $brand->name;
    }

    protected $rules = [
        'name'   => 'required|min:3'
    ];

    protected $messages = [
        'name.required'    => 'El nombre es requerido.',
        'name.min'    => 'El campo nombre debe tener al menos 3 caracteres.'
    ];

    protected $validationAttributes = [
        'name' => 'nombre'
    ];

    public function render()
    {
        return view('livewire.brands.edit-brand');
    }

    public function save(){
        $this->validate();
        $brand = Brand::find($this->id)->update(['name' => $this->name]);
        $this->dispatch('success',['label' => 'Se edito la marca con éxito.','btn' => 'Ir a marcas','route' => route('brands.index')]);
    }

}
