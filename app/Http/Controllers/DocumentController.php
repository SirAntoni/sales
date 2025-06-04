<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\MigoApiService;
use App\Services\SunatService;
use Greenter\Model\Sale\Invoice;
use Greenter\Report\HtmlReport;
use Illuminate\Http\Request;
use Greenter\Report\PdfReport;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('documents.index');
    }

    public function creditNote(string $id)
    {
        return view('documents.credit-note', compact('id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function download(string $path)
    {
        $fullPath = storage_path($path);
        return response()->download($fullPath);
    }

    public function retry(Document  $document,MigoApiService $api)
    {
        $docConfig = [
            'DNI' => [
                'size'        => 8,
                'field'       => 'dni',
                'responseKey' => 'nombre',
            ],
            'RUC' => [
                'size'        => 11,
                'field'       => 'ruc',
                'responseKey' => 'nombre_o_razon_social',
            ],
        ];

        $tiposIdentidad = [
            '0' => 'NO DOMICILIADO',
            '1' => 'DNI',
            '4' => 'CE',
            '6' => 'RUC',
            '7' => 'PASAPORTE',
        ];

        $inverseTipos = array_flip($tiposIdentidad);

        $textoDoc = $document->client->document_type;
        $tipoDoc  = $inverseTipos[$textoDoc] ?? null;

        $config = $docConfig[$textoDoc];

        $payload = [
            $config['field'] => $document->client->document_number,
            'token'          => env('MIGO_API_TOKEN')
        ];

        $responseMigoApi = $api->post(
            strtolower($document->client->document_type),
            $payload
        );

        $formatter = new NumeroALetras();

        $legends = $formatter->toInvoice($document->total, 2, 'SOLES');

        $sunat = new SunatService();

        $data = [
            "serie" => $document->serie,
            "correlative" => $document->correlative,
            "date" => $document->date ?? "2005-01-01",
            "tipoDoc" => ($document->document_type == '1') ? '01' : '03',
            "subtotal" => number_format((float)$document->subtotal, 2, '.', ''),
            "igv"=> number_format((float)$document->tax, 2, '.', ''),
            "total" => number_format((float)$document->total, 2, '.', ''),
            "client" => [
                "tipoDoc" => $tipoDoc,
                "numDoc" => $document->client->document_number,
                "name" => $responseMigoApi[$config['responseKey']] ?? '',
                "address" => $document->client->address,
            ],
            "items"=> $document->documentDetails,
            "legend"=> $legends,
        ];

        $see = $sunat->getSee();

        $invoice = $sunat->getInvoice($data);

        Log::info("invoice: " . json_encode($data));;

        $result = $see->send($invoice);

        file_put_contents(storage_path('/xml_path/'.$invoice->getName().'.xml'),$see->getFactory()->getLastXml());

        $sunatResponse = $sunat->sunatResponse($invoice,$result);

        $pdf_path = "";
        if($sunatResponse['status'] == 1){
            $pdf_path = $sunat->generatePDF($invoice);
        }

        if($sunatResponse['status'] != 1){
            return response()->json(['no enviado']);
        }

        return response()->json(['success']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('documents.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
