<?php

namespace App\Livewire\PaymentMethods;

use Livewire\Component;
use App\Models\PaymentMethod;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class TablePaymentMethods extends Component
{
    use WithPagination;
    public $search = "";

    public function updatingSearch(){
        $this->resetPage();
    }

    #[On('destroy')]
    public function destroy($id){
        PaymentMethod::destroy($id);
        $this->render();
    }

    public function edit($id){
        return redirect()->route('payment-methods.show',$id);
    }
    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar el método de pago?.','btn' => 'Eliminar','route' => route('payment-methods.index'),'id' => $id]);
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::orderBy('id','desc')->when($this->search,function($query){
            $query->where('name','like','%'.$this->search.'%');
        })->paginate('15');

        return view('livewire.payment-methods.table-payment-methods',compact('paymentMethods'));
    }
}
