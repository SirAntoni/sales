<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class TableContacts extends Component
{
    use WithPagination;
    public $search = "";

    public function updatingSearch(){
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id){
        Contact::destroy($id);
        $this->render();
    }

    public function edit($id){
        return redirect()->route('contacts.show',$id);
    }
    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el contacto?.','btn' => 'Eliminar','route' => route('contacts.index'),'id' => $id]);
    }

    public function render()
    {
        $contacts = Contact::orderBy('id','desc')->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%');
        })->paginate('15');

        return view('livewire.contacts.table-contacts',compact('contacts'));
    }
}
