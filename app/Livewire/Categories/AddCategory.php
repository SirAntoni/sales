<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class AddCategory extends Component
{

    public $names = [''];

    protected $rules = [
        'names'   => 'required|array|min:1',
        'names.*' => 'required|string|min:3',
    ];

    protected $messages = [
        'names.*.required'    => 'El nombre es requerido.',
        'names.*.min'    => 'El campo nombre debe tener al menos 3 caracteres.',
    ];

    public function addField()
    {
        $this->names[] = ''; // añadimos un nuevo elemento al array
    }
    public function save(){
        $this->validate();
        foreach ($this->names as $categoryName) {
            Category::create([
                'name' => $categoryName,
            ]);
        }

        $this->dispatch('success',['label' => 'Se agrego la(s) categoria(s) con éxito.','btn' => 'Ir a categorías','route' => route('categories.index')]);
    }
    public function render()
    {
        return view('livewire.categories.add-category');
    }
}
