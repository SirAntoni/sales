<?php

namespace App\Livewire\Documents;

use App\Models\Article;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Document;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Middleware\ValidatePostSize;
use Livewire\Component;
use App\Models\Voucher;
use Luecano\NumeroALetras\NumeroALetras;

class NewDocument extends Component
{
    public $id;
    public $clients;
    public $client;
    public $defaultClient;
    public $articles;
    public $number;
    public $date;
    public $expirationDate;
    public $granSubtotal;
    public $granTax;
    public $granTotal;
    public $dateSelected;
    public $articleSelected;
    public $articlesSelected = [];

    public $clientSelected;


    public $serie;
    public $correlative;
    public $documentType;

    public $legends;

    public function mount(){
        $sale = Sale::find($this->id);

        $this->serie = "-";
        $this->correlative = "-";

        //Inicio Client
        $this->client = $sale->client_id;
        $this->clientSelected = $sale->client;
        //Fin Client

        $this->date = $sale->date;
        $this->defaultClient = $sale->client->id;
        $this->number = $sale->number;
        $this->granSubtotal = $sale->granSubtotal;

        foreach ($sale->saleDetails as $detail) {
            $this->addToArticleSale($detail->id);
        }
    }

    protected $rules = [
        'date'=>'required',
        'expirationDate' => 'required',
        'documentType' => 'required',
        'articlesSelected' => 'required|array|min:1',
        'client' => 'required',
    ];

    public function save()
    {
        $this->validate();
        $document = Document::create([
            'document_type' => $this->documentType,
            'serie' => $this->serie,
            'correlative' => $this->correlative,
            'date' => $this->date,
            'expiration_date' => $this->expirationDate,
            'currency' => 'PEN',
            'payment_method' => 'CONTADO',
            'subtotal' => $this->granSubtotal,
            'tax' => $this->granTax,
            'total' => $this->granTotal,
            'xml_path' => 'xml/path',
            'cdr_path' => 'cdr/path',
            'status_sunat'=> '001',
            'sale_id' => $this->id,
            'client_id' => $this->client,
            'user_id' => auth()->id()
        ]);

        foreach ($this->articlesSelected as $article) {
            $art = Article::find($article['id']);
            $document->documentDetails()->create([
                'price' => $article['price'],
                'quantity' => $article['quantity'],
                'tax' => $article['total'] * 0.18,
                'total' => $article['total'] + ($article['total'] * 0.18),
                'article_id' => $article['id'],
                'category_id' => $article['category'],
                'brand_id' => $article['brand'],
                'subtotal' => $article['total'],
            ]);

        }
        $this->dispatch('success', ['label' => 'La documento fue registrada con éxito.', 'btn' => 'Ir a documentos', 'route' => route('documents.index')]);
    }

    public function searchClients($query)
    {
        return Client::query()
            ->where('name', 'like', '%'.$query.'%')
            ->limit(10)
            ->get(['id', 'name'])
            ->map(fn($c) => [
                'value' => $c->id,
                'text'  => $c->name,
            ])
            ->toArray();
    }

    public function updatedDocumentType(){
        $this->serie = Voucher::serie($this->documentType);
        $this->correlative = Voucher::nextCorrelativeById($this->documentType);
    }

    public function searchArticles($query)
    {
        $qb = Article::query()
            ->where('title', 'like', '%'.$query.'%')
            ->limit(10)
            ->get(['id', 'title', 'stock', 'sale_price', 'purchase_price']);

        $showPurchase = auth()->id() === 1;

        return $qb->map(function($c) use ($showPurchase) {
            $text = "{$c->title} | Stock: {$c->stock} | Precio Venta: S/.{$c->sale_price}";

            if ($showPurchase) {
                $text .= " | Precio Compra: $ {$c->purchase_price}";
            }

            return [
                'value' => $c->id,
                'text'  => $text,
            ];
        })->toArray();
    }

    public function updatedArticleSelected($id)
    {
        if ($id) {
            $this->addToArticle($id);
            $this->articleSelected = null;
        }
    }

    public function addToArticle($id)
    {

        $article = Article::find($id);

        if ($article) {

            $index = collect($this->articlesSelected)->search(function ($item) use ($article) {
                return $item['id'] == $article->id;
            });

            if ($index !== false) {

                if ($this->articlesSelected[$index]['quantity'] < $article->stock) {
                    $this->articlesSelected[$index]['quantity']++;
                    $this->articlesSelected[$index]['total'] = $this->articlesSelected[$index]['quantity'] * $article->purchase_price;
                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }

            } else {

                if ($article->stock > 0) {

                    $this->articlesSelected[] = [
                        'id' => $article->id,
                        'category' => $article->category_id,
                        'brand' => $article->brand_id,
                        'title' => $article->title,
                        'price' => $article->sale_price,
                        'quantity' => 1,
                        'total' => $article->sale_price
                    ];



                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }
            }

            $this->calculateTotals();
        }
    }

    public function addToArticleSale($id)
    {

        $article = SaleDetail::with('article')->find($id);


        if ($article) {

            $index = collect($this->articlesSelected)->search(function ($item) use ($article) {
                return $item['id'] == $article->id;
            });

            if ($index !== false) {

                if ($this->articlesSelected[$index]['quantity'] < $article->stock) {
                    $this->articlesSelected[$index]['quantity']++;
                    $this->articlesSelected[$index]['total'] = $this->articlesSelected[$index]['quantity'] * $article->purchase_price;
                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }

            } else {

                if ($article->article->stock > 0) {

                    $this->articlesSelected[] = [
                        'id' => $article->article->id,
                        'category' => $article->article->category_id,
                        'brand' => $article->brand_id,
                        'title' => $article->article->title,
                        'price' => $article->price,
                        'quantity' => $article->quantity,
                        'total' => $article->price * $article->quantity,
                    ];



                } else {
                    $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
                }
            }

            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->granSubtotal = collect($this->articlesSelected)->sum('total');
        $this->granTax = $this->granSubtotal * 0.18;
        $this->granTotal = $this->granSubtotal + $this->granTax;

        $formatter = new NumeroALetras();

        $this->legends = $formatter->toInvoice($this->granTotal, 2, 'SOLES');
    }

    public function updateTotal($index)
    {

        if (!isset($this->articlesSelected[$index])) {
            return;
        }

        $selected = &$this->articlesSelected[$index];

        $article = Article::find($selected['id']);
        if (!$article) {
            $this->dispatch('error', ['label' => 'Artículo no encontrado']);
            return;
        }

        if ($article->stock < $selected['quantity']) {
            $this->dispatch('error', ['label' => 'No hay stock disponible para ' . $article->title]);
            $selected['quantity'] = $article->stock;
        }

        $selected['total'] = (float)$selected['price'] * (int)$selected['quantity'];
        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.documents.new-document');
    }
}
