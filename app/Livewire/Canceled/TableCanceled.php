<?php

namespace App\Livewire\Canceled;

use Livewire\Component;
use App\Models\Sale;

class TableCanceled extends Component
{
    public $search;
    public function rendered(){
        $this->dispatch('reinit-tippy');
    }
    public function edit($id)
    {
        return redirect()->route('sales.show', $id);
    }
    public function render()
    {

        $pages = 30;
        $sales = Sale::orderBy('id', 'desc')
            ->where('status', 0)
            ->whereYear('created_at', '2025')
            ->when($this->search, function ($query) {
                $query->whereHas('client', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($pages);

        foreach ($sales as $sale) {

            $htmlDetails = "<p><strong>Cliente: </strong> {$sale->client->name} </p><br><table style='border: 1px solid;'><thead style='border:1px solid;'><tr><th style='border:1px solid'>Titulo</th><th style='border:1px solid;padding:10px'>Cantidad</th><th style='padding:10px'>Precio</thstyle></tr></thead><tbody style='border:1px solid;'>";

            foreach ($sale->saleDetails as $detail) {
                $htmlDetails .= "<tr><td style='border:1px solid;padding:5px'>{$detail->article->title}</td><td style='text-align: center;border:1px solid;'>{$detail->quantity}</td><td style='text-align: center;border:1px solid;'>{$detail->price}</td></tr>";
            }
            $sale->htmlDetails = $htmlDetails . '</tbody></table>';

        }

        return view('livewire.canceled.table-canceled', compact('sales'));
    }
}
