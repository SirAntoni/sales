<?php

namespace App\Livewire\Vouchers;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class TableVouchers extends Component
{
    public $search;
    use WithPagination;
    public function updatingSearch(){
        $this->resetPage();
    }


    #[On('destroy')]
    public function destroy($id){
        Voucher::destroy($id);
        $this->render();
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el comprobante?.','btn' => 'Eliminar','route' => route('vouchers.index'),'id' => $id]);
    }

    public function edit($id){
        return redirect()->route('vouchers.show',$id);
    }

    public function render()
    {
        $vouchers = Voucher::orderBy('id','desc')->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%');
        })->paginate('15');
        return view('livewire.vouchers.table-vouchers',compact('vouchers'));
    }
}
