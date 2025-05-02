<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class TableClients extends Component
{
    public $search;
    use WithPagination;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function edit($id){
        return redirect()->route('clients.show',$id);
    }

    #[On('destroy')]
    public function destroy($id){
        Client::destroy($id);
        $this->render();
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el cliente?.','btn' => 'Eliminar','route' => route('clients.index'),'id' => $id]);
    }

    public function render()
    {
        $pages = 15;

        $clients = DB::table('clients')
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($pages);




        return view('livewire.clients.table-clients', compact('clients'));
    }
}
