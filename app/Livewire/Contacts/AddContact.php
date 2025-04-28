<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use App\Models\Contact;

class AddContact extends Component
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
        foreach ($this->names as $contactName) {
            Contact::create([
                'name' => $contactName,
            ]);
        }
        $this->dispatch('success',['label' => 'Se agrego lo(s) contacto(s) con éxito.','btn' => 'Ir a contactos','route' => route('contacts.index')]);
    }

    public function render()
    {
        return view('livewire.contacts.add-contact');
    }
}
