<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Log;

class TableCategories extends Component
{
    use WithPagination;
    public $search = "";

    public function updatingSearch(){
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id){
        Category::destroy($id);
        $this->render();
    }

    public function edit($id){
        return redirect()->route('categories.show',$id);
    }
    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar la categoria?.','btn' => 'Eliminar','route' => route('categories.index'),'id' => $id]);
    }
    public function render()
    {

        $categories = Category::orderBy('id','desc')->when($this->search,function($query){
                $query->where('name','like','%'.$this->search.'%');
            })->paginate('15');
        Log::info("Registros filtrados por categoria ".$this->search);
        return view('livewire.categories.table-categories', compact('categories'));
    }



}
