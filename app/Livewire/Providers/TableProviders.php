<?php

namespace App\Livewire\Providers;

use App\Models\Provider;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TableProviders extends Component
{
    use WithPagination;
    public $search = '';
    public $filter= '';

    public function updatingSearch(){
        $this->resetPage();
    }
    #[On('destroy')]
    public function destroy($id){
        Provider::destroy($id);
        $this->render();
    }

    public function edit($id){
        return redirect()->route('providers.show',$id);
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el proveedor?.','btn' => 'Eliminar','route' => route('providers.index'),'id' => $id]);
    }

    public function render()
    {
        $pages = 15;
        $providers = [];
        switch ($this->filter) {
            case 'document':
                $providers = Provider::orderBy('id', 'desc')->when($this->search, function ($query) {
                    $query->where('document_number', 'like', '%' . $this->search . '%');
                })->paginate($pages);
                break;
                break;
            default:
                $providers = Provider::orderBy('id', 'desc')->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })->paginate($pages);
                break;
        }

        return view('livewire.providers.table-providers', compact('providers'));
    }
}
