<?php

namespace App\Livewire\Brands;

use App\Models\Brand;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Log;

class TableBrands extends Component
{
    use WithPagination;
    public $search = "";

    public function updatingSearch(){
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id){
        Brand::destroy($id);
        $this->render();
    }

    public function edit($id){
        return redirect()->route('brands.show',$id);
    }
    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar la marca?.','btn' => 'Eliminar','route' => route('brands.index'),'id' => $id]);
    }
    public function render()
    {
        $brands = Brand::select('id','name','created_at')->orderBy('id','desc')->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%');
        })->paginate('15');

        return view('livewire.brands.table-brands', compact('brands'));
    }



}
