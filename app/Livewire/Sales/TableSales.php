<?php

namespace App\Livewire\Sales;

use App\Models\Article;
use Livewire\Component;
use App\Models\Sale;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Log;

class TableSales extends Component
{
    use WithPagination;
    const SALE_CANCELED = 0;
    const SALE_PENDING = 1;
    const SALE_APPROVED = 2;
    const SALE_OBSERVATION = 3;
    public $search;
    public $details;
    public $titleNotification;
    public $bodyNotification;

    public $startDate;
    public $endDate;

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

    public function getDetails()
    {

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

        $sale->update(['status' => self::SALE_CANCELED]);
        $this->render();
    }

    public function delete($id)
    {
        $this->dispatch('delete', ['label' => 'Esta seguro que desea anular la venta?.', 'btn' => 'Eliminar', 'route' => route('sales.index'), 'id' => $id]);
    }

    public function changeStatus($id)
    {
        $sale = Sale::find($id);

        if ($sale->status === self::SALE_APPROVED || $sale->status === self::SALE_PENDING) {
            $sale->status = ($sale->status === self::SALE_APPROVED)
                ? self::SALE_PENDING
                : self::SALE_APPROVED;
            $sale->save();
            $this->dispatch('notification');
        }
    }

    public function verPDF($id)
    {
        $url = route('pdf.view', ['id' => $id]);
        $this->dispatch('abrir-nueva-pestania', ['url' => $url]);
    }

    public function render()
    {

        $limit = 15;
        $sales = Sale::query()
            ->with(['saleDetails', 'client:id,name', 'contact:id,name', 'paymentMethod:id,name'])
            ->where('status', '!=', 0)
            ->when($this->search, function ($query, $search) {
                $search = trim($search);
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                        ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('contact', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('paymentMethod', fn ($p) => $p->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('id')
            ->paginate($limit);

        foreach ($sales as $sale) {
            $htmlDetails = "<p><strong>Cliente: </strong> {$sale->client->name} </p><br><table style='border: 1px solid;'><thead style='border:1px solid;'><tr><th style='border:1px solid'>Titulo</th><th style='border:1px solid;padding:10px'>Cantidad</th><th style='padding:10px'>Precio</thstyle></tr></thead><tbody style='border:1px solid;'>";
            $btnDetails = '';
            switch($sale->status) {
                case self::SALE_APPROVED:
                    $btnDetails = 'success';
                    break;
                case self::SALE_OBSERVATION:
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
            $sale->btnDetails = $btnDetails;

        }

        return view('livewire.sales.table-sales', compact('sales'));
    }
}
