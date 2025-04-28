<?php

namespace App\Livewire\Providers;
use App\Models\Provider;

use Livewire\Component;

class EditProvider extends Component
{
    public $id;
    public $name;
    public $document_number;
    public $document_type;
    public $address;
    public $phone;
    public $email;

    protected $rules = [
        'name' => 'required|string|min:3',
        'document_number' => 'required|numeric|min:3',
        'document_type' => 'required|string|min:2',
        'address' => 'nullable|string|min:3',
        'phone' => 'nullable|numeric|min:3',
        'email' => 'nullable|email'
    ];

    protected $validationAttributes = [
        'name' => 'nombre',
        'document_number' => 'documento',
        'document_type' => 'tipo de documento',
        'address' => 'dirección',
        'phone' => 'teléfono',
        'email' => 'correo'
    ];


    public function mount(){
        $provider = Provider::find($this->id);
        $this->name = $provider->name;
        $this->document_number = $provider->document_number;
        $this->document_type = $provider->document_type;
        $this->address = $provider->address;
        $this->phone = $provider->phone;
        $this->email = $provider->email;

    }

    public function save(){
        $this->validate();
        $provider = Provider::find($this->id)->update([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'document_type' => $this->document_type,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email
        ]);
        $this->dispatch('success',['label' => 'Se edito el proveedor con éxito.','btn' => 'Ir a proveedores','route' => route('providers.index')]);
    }

    public function render()
    {
        return view('livewire.providers.edit-provider');
    }
}
