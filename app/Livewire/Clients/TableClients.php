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
    public $filter;
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
        $clients = [];
        switch ($this->filter) {
            case 'document':
                $clients = DB::table('clients')->orderBy('id', 'desc')->when($this->search, function ($query) {
                    $query->where('document_number', 'like', '%' . $this->search . '%');
                })->paginate($pages);
                break;
                break;
            default:
                $clients = DB::table('clients')->orderBy('id', 'desc')->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })->paginate($pages);
                break;
        }

        return view('livewire.clients.table-clients', compact('clients'));
    }
}
