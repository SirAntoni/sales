<?php

namespace App\Livewire\Documents;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class TableDocuments extends Component
{
    public $search;
    use WithPagination;

    public function updatingSearch(){
        $this->resetPage();
    }
    public function render()
    {
        $invoices = Invoice::where('id','!=',2)->paginate(15);
        return view('livewire.documents.table-documents',compact('invoices'));
    }
}
