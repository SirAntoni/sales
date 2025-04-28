<?php

namespace App\Livewire\Purchases;

use App\Models\Article;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Purchase;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class TablePurchases extends Component
{

    const PURCHASE_CANCELED = 0;
    const PURCHASE_SUCCESS = 1;
    const PURCHASE_PARTIAL = 2;


    use WithPagination;
    public $search;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function changeStatus($id)
    {
        $purchase = Purchase::find($id);

        if ($purchase->status === self::PURCHASE_SUCCESS || $purchase->status === self::PURCHASE_PARTIAL) {
            $purchase->status = ($purchase->status === self::PURCHASE_SUCCESS)
                ? self::PURCHASE_PARTIAL
                : self::PURCHASE_SUCCESS;
            $purchase->save();
            $this->dispatch('notification');
        }
    }

    public function edit($id){
        return redirect()->route('purchases.show',$id);
    }

    #[On('destroy')]
    public function destroy($id){
        $purchase = Purchase::find($id);
        DB::transaction(function () use ($purchase) {
            $articleIds = $purchase->purchaseDetails->pluck('article_id')->unique();

            $articles = Article::whereIn('id', $articleIds)->get()->keyBy('id');

            foreach ($purchase->purchaseDetails as $item) {
                if (isset($articles[$item->article_id])) {
                    $article = $articles[$item->article_id];
                    $article->stock -= $item->quantity;
                    $article->save();
                } else {
                    throw new \Exception("Artículo no encontrado: {$item->article_id}");
                }
            }
        });
        $purchase->update(['status' => 0]);
        $this->render();
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar la compra?.','btn' => 'Eliminar','route' => route('purchases.index'),'id' => $id]);
    }

    public function render()
    {
        $pages = 15;
        $purchases = Purchase::orderBy('id', 'desc')
            ->whereNot('status', 0)
            ->when($this->search, function ($query) {
                $query->whereHas('provider', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($pages);

        foreach ($purchases as $purchase) {
            $btnColor = '';
            switch($purchase->status) {
                case self::PURCHASE_SUCCESS:
                    $btnColor = 'success';
                    break;
                case self::PURCHASE_PARTIAL:
                    $btnColor = 'warning';
                    break;
                default:
                    $btnColor = 'info';
                    break;
            }

            $purchase->btnColor = $btnColor;
            $purchase->btnSize = "sm";

        }

        return view('livewire.purchases.table-purchases', compact('purchases'));
    }
}
