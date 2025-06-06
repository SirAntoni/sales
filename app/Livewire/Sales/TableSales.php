<?php

namespace App\Livewire\Sales;

use App\Models\Article;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Sale;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Log;

class TableSales extends Component
{
    use WithPagination;

    public $search;
    public $details;
    public $titleNotification;
    public $bodyNotification;

    public $startDate;
    public $endDate;
    public $limit;

    public function mount(){
        $this->limit = 40;
        $this->startDate = null;
        $this->endDate = null;
    }
    public function updatingSearch(){
        $this->resetPage();
    }

    public function rendered(){
        $this->dispatch('reinit-tippy');
    }

    public function edit($id)
    {
        return redirect()->route('sales.show', $id);
    }

    public function newDocument($id)
    {
        return redirect()->route('documents.show', $id);
    }

    #[On('destroy')]
    public function destroy($id)
    {

        $sale = Sale::find($id);

        DB::transaction(function () use ($sale) {
            $articleIds = $sale->saleDetails->pluck('article_id')->unique();

            $articles = Article::whereIn('id', $articleIds)->get()->keyBy('id');

            foreach ($sale->saleDetails as $item) {
                if (isset($articles[$item->article_id])) {
                    $article = $articles[$item->article_id];
                    $article->stock += $item->quantity;
                    $article->save();
                } else {
                    throw new \Exception("Artículo no encontrado: {$item->article_id}");
                }
            }
        });

        $sale->update(['status' => Sale::SALE_CANCELED,'updated_at' => now()]);
        $this->render();
    }

    public function delete($id)
    {
        $this->dispatch('delete', ['label' => 'Esta seguro que desea anular la venta?.', 'btn' => 'Eliminar', 'route' => route('sales.index'), 'id' => $id]);
    }

    public function changeStatus($id)
    {
        if(auth()->user()->can('update')){
            $sale = Sale::find($id);
            if ($sale->status == Sale::SALE_APPROVED || $sale->status == Sale::SALE_PENDING) {
                $sale->status = ($sale->status == Sale::SALE_APPROVED)
                    ? Sale::SALE_PENDING
                    : Sale::SALE_APPROVED;
                $sale->save();
                $this->dispatch('notification');
            }
        }
    }

    public function verPDF($id)
    {
        $url = route('pdf.view', ['id' => $id]);
        $this->dispatch('abrir-nueva-pestania', ['url' => $url]);
    }

    public function render()
    {

        $limit = $this->limit ?? 40;
        $sales = Sale::query()
            ->with(['saleDetails','document', 'client:id,name', 'contact:id,name', 'paymentMethod:id,name'])
            ->where('status', '!=', Sale::SALE_CANCELED)
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                        $q->where('number', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('contact', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('paymentMethod', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('saleDetails.article', fn($a) =>
                            $a->where('title', 'like', "%{$search}%")
                        );
                });
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('sales.created_at', [
                    // formatea según tu columna; si usas created_at, cámbialo
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(),
                ]);
            })
            ->orderByDesc('id')
            ->paginate($limit);

        foreach ($sales as $sale) {
            $htmlDetails = "<p><strong>Cliente: </strong> {$sale->client->name} </p><br><table style='border: 1px solid;'><thead style='border:1px solid;'><tr><th style='border:1px solid'>Titulo</th><th style='border:1px solid;padding:10px'>Cantidad</th><th style='padding:10px'>Precio</thstyle></tr></thead><tbody style='border:1px solid;'>";
            $btnDetails = '';
            switch($sale->status) {
                case Sale::SALE_APPROVED:
                    $btnDetails = 'success';
                    break;
                case Sale::SALE_OBSERVATION:
                    $btnDetails = 'warning';
                    break;
                default:
                    $btnDetails = 'dark';
                    break;
            }
            foreach ($sale->saleDetails as $detail) {
                $htmlDetails .= "<tr><td style='border:1px solid;padding:5px'>{$detail->article->title}</td><td style='text-align: center;border:1px solid;'>{$detail->quantity}</td><td style='text-align: center;border:1px solid;'>{$detail->price}</td></tr>";
            }
            $sale->htmlDetails = $htmlDetails . '</tbody></table>';
            if($sale->observations != null){
                $sale->htmlDetails .= "<br><p>Observaciones: ".$sale->observations. "</p><br>";
            }
            $sale->btnDetails = $btnDetails;

        }

        return view('livewire.sales.table-sales', compact('sales'));
    }
}
