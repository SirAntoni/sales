<?php

namespace App\Livewire\Kardex;

use App\Models\Article;
use Livewire\Component;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;
class Kardex extends Component
{

    public $kardex = [];
    public $articles = [];
    public $article;

    public function mount(){

        $this->articles = Article::select('id','title','stock','sale_price','sku')->where('stock', '>', 0)
            ->whereNot('id', 1)
            ->OrderBy('id', 'desc')
            ->get();
    }

    public function updatedArticle($id)
    {
        $this->article = $id;
        $this->getKardex();
    }

    public function getKardex()
    {
        // Movimientos de compras (entradas) con nombre del artículo
        $purchaseMovements = PurchaseDetail::select(
            'purchase_details.article_id',
            'articles.title as article_name',
            DB::raw("'contact' as contact_name"),
            DB::raw("'client' as client_name"),
            'users.name as user_name',
            'purchases.number as number',
            DB::raw("purchases.created_at as fecha"),
            DB::raw("'entrada' as tipo"),
            'purchase_details.quantity as cantidad'
        )->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->join('articles', 'articles.id', '=', 'purchase_details.article_id')
            ->join('users', 'users.id', '=', 'purchases.user_id')
            ->where('purchases.status', 1)
            ->where('purchase_details.article_id', $this->article);

        // Movimientos de ventas (salidas) con nombre del artículo
        $saleMovements = SaleDetail::select(
            'sale_details.article_id',
            'articles.title as article_name',
            'contacts.name as contact_name',
            'clients.name as client_name',
            'users.name as user_name',
            'sales.number as number',
            DB::raw("sales.created_at as fecha"),
            DB::raw("'salida' as tipo"),
            DB::raw("sale_details.quantity as cantidad")
        )
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->join('contacts', 'contacts.id', '=', 'sales.contact_id')
            ->join('clients', 'clients.id', '=', 'sales.client_id')
            ->join('articles', 'articles.id', '=', 'sale_details.article_id')
            ->where('sales.status',1)
            ->where('sale_details.article_id', $this->article);

        // Unificar ambas consultas
        $movements = $purchaseMovements->union($saleMovements);

        // Obtener los movimientos ordenados por fecha
        $kardex = DB::table(DB::raw("({$movements->toSql()}) as movimientos"))
            ->mergeBindings($movements->getQuery()) // Transfiere los parámetros correctamente
            ->orderBy('fecha')
            ->get();

        // Calcular el saldo acumulado
        $saldo = 0;
        $kardexConSaldo = $kardex->map(function($movimiento) use (&$saldo) {
            if ($movimiento->tipo === 'entrada') {
                $saldo += $movimiento->cantidad;
            } else {
                // Para salidas, restamos la cantidad
                $saldo -= $movimiento->cantidad;
            }
            $movimiento->saldo = $saldo;
            return $movimiento;
        });


        $this->kardex =  $kardexConSaldo;

    }

    public function render()
    {
        return view('livewire.kardex.kardex');
    }
}
