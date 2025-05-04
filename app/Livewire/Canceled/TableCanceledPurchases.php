<?php

namespace App\Livewire\Canceled;
use App\Models\Purchase;

use Livewire\Component;

class TableCanceledPurchases extends Component
{
    public $search;

    public function rendered(){
        $this->dispatch('reinit-tippy');
    }

    public function edit($id)
    {
        return redirect()->route('purchases.show', $id);
    }

    public function render()
    {

        $limit = 15;
        $purchases = Purchase::query()
            ->with(['purchaseDetails', 'provider:id,name'])
            ->where('status', 0)
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('document', 'like', "%{$search}%")
                        ->where('passenger', 'like', "%{$search}%")
                        ->orWhereHas('provider', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate($limit);

        foreach ($purchases as $purchase) {

            $htmlDetails = "<p><strong>Proveedor: </strong> {$purchase->provider->name} </p><br><table style='border: 1px solid;'><thead style='border:1px solid;'><tr><th style='border:1px solid'>Titulo</th><th style='border:1px solid;padding:10px'>Cantidad</th><th style='padding:10px'>Precio</thstyle></tr></thead><tbody style='border:1px solid;'>";

            foreach ($purchase->purchaseDetails as $detail) {
                $htmlDetails .= "<tr><td style='border:1px solid;padding:5px'>{$detail->article->title}</td><td style='text-align: center;border:1px solid;'>{$detail->quantity}</td><td style='text-align: center;border:1px solid;'>{$detail->price}</td></tr>";
            }
            $purchase->htmlDetails = $htmlDetails . '</tbody></table>';

        }

        return view('livewire.canceled.table-canceled-purchases',compact('purchases'));
    }
}
