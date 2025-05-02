<?php

namespace App\Livewire\Providers;

use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TableProviders extends Component
{
    use WithPagination;
    public $search = '';

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
        $providers = DB::table('providers')
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

        return view('livewire.providers.table-providers', compact('providers'));
    }
}
