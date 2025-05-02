<?php

namespace App\Livewire\Users;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

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
        $limit = 15;
        $users = DB::table('users')
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($limit);



        return view('livewire.users.table-users', compact('users'));
    }
}
