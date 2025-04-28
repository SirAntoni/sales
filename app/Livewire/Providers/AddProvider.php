<?php

namespace App\Livewire\Providers;
use App\Models\Provider;

use Livewire\Component;

class AddProvider extends Component
{

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

    public function save(){
        $this->validate();
        Provider::create([
            'name' => $this->name,
            'document_number' => $this->document_number,
            'document_type' => $this->document_type,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email
        ]);

        $this->dispatch('success',['label' => 'Se agrego el proveedor con éxito.','btn' => 'Ir a proveedores','route' => route('providers.index')]);
    }


    public function render()
    {
        return view('livewire.providers.add-provider');
    }
}
