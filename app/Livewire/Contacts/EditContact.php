<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use App\Models\Contact;

class EditContact extends Component
{
    public $id;
    public $name;

    protected $rules = [
        'name'   => 'required'
    ];
    public function mount(){
        $contact = Contact::find($this->id);
        $this->name = $contact->name;
    }
    public function save(){
        $this->validate();
        Contact::find($this->id)->update(['name' => $this->name]);
        $this->dispatch('success',['label' => 'Se edito el contacto con éxito.','btn' => 'Ir a contactos','route' => route('contacts.index')]);
    }
    public function render()
    {
        return view('livewire.contacts.edit-contact');
    }
}
