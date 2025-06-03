<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class TableDocuments extends Component
{
    public $search;
    use WithPagination;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function creditNote($id){
        return redirect()->route('documents.credit-note', $id);
    }
    public function render()
    {
        $documents = Document::paginate(15);
        return view('livewire.documents.table-documents',compact('documents'));
    }
}
