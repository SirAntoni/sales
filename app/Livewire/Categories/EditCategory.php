<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class EditCategory extends Component
{
    public $name = '';
    public $id;

    public function mount(){
        $category = Category::find($this->id);
        $this->name = $category->name;
    }

    protected $rules = [
        'name'   => 'required|min:3'
    ];

    protected $messages = [
        'name.required'    => 'El nombre es requerido.',
        'name.min'    => 'El campo nombre debe tener al menos 3 caracteres.'
    ];
    public function render()
    {

        return view('livewire.categories.edit-category');
    }

    public function save(){
        $this->validate();
        $category = Category::find($this->id)->update(['name' => $this->name]);
        $this->dispatch('success',['label' => 'Se edito la categoría con éxito.','btn' => 'Ir a categorías','route' => route('categories.index')]);
    }

}
