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

    use WithPagination;
    public $search;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function changeStatus($id)
    {
        $purchase = Purchase::find($id);

        if ($purchase->status === Purchase::PURCHASE_FINISHED || $purchase->status === Purchase::PURCHASE_NOT_FINISHED) {
            $purchase->status = ($purchase->status === Purchase::PURCHASE_FINISHED)
                ? Purchase::PURCHASE_NOT_FINISHED
                : Purchase::PURCHASE_FINISHED;
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
        $purchase->update(['status' => Purchase::PURCHASE_CANCELED,'updated_at' => now()]);
        $this->render();
    }

    public function delete($id){
        $this->dispatch('delete',['label' => 'Esta seguro que desea eliminar la compra?.','btn' => 'Eliminar','route' => route('purchases.index'),'id' => $id]);
    }

    public function render()
    {
        $limit = 15;
        $purchases = Purchase::query()
            ->with(['provider:id,name'])
            ->where('status', '!=', Purchase::PURCHASE_CANCELED)
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhere('passenger', 'like', "%{$search}%")
                        ->orWhereHas('provider', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate($limit);

        foreach ($purchases as $purchase) {
            $btnColor = '';
            switch($purchase->status) {
                case Purchase::PURCHASE_FINISHED:
                    $btnColor = 'success';
                    break;
                case Purchase::PURCHASE_NOT_FINISHED:
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
