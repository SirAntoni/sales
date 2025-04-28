<?php

namespace App\Livewire\Users;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class TableUsers extends Component
{

    public $search;

    public function edit($id){
        return redirect()->route('users.show',$id);
    }

    #[On('destroy')]
    public function destroy($id){
        User::destroy($id);
        $this->render();
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el usuario?.','btn' => 'Eliminar','route' => route('users.index'),'id' => $id]);
    }

    public function render()
    {
        $users = User::orderBy('id','desc')->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%');
        })->paginate('15');
        return view('livewire.users.table-users', compact('users'));
    }
}
