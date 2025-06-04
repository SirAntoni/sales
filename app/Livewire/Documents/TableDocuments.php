<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Services\SunatService;
use DateTime;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

use Greenter\Model\Response\SummaryResult;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

class TableDocuments extends Component
{
    public $search;
    use WithPagination;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('document_destroy')]
    public function document_destroy(Document $document)
    {

        $sunat = new SunatService();

        $see = $sunat->getSee();
        $detail1 = new VoidedDetail();

        $data = [
            "serie" => $this->serie,
            "correlative" => $this->correlative,
            "date" => $this->date ?? "2005-01-01",
            "tipoDoc" => ($this->documentType == '1') ? '01' : '03',
            "subtotal" => $this->granSubtotal,
            "igv"=> $this->granTax,
            "total" => $this->granTotal,
            "client" => [
                "tipoDoc" => $tipoDoc,
                "numDoc" => $client->document_number,
                "name" => $responseMigoApi[$config['responseKey']] ?? '',
                "address" => $client->address,
            ],
            "items"=> $items,
            "legend"=> $this->legends,
        ];

        $detail1->setTipoDoc(($document->document_type == '1') ? '01' : '03')
            ->setSerie($document->serie)
            ->setCorrelativo($document->correlative)
            ->setDesMotivoBaja('PRUEBAS DE INTEGRACIÓN');

        $voided = new Voided();
        $voided->setCorrelativo(1)
            // Fecha Generacion menor que Fecha comunicacion
            ->setFecGeneracion(new DateTime('-3days'))
            ->setFecComunicacion(new DateTime('-1days'))
            ->setCompany($util->shared->getCompany())
            ->setDetails([$detail1]);


        $result = $see->send($voided);

        file_put_contents(storage_path('/xml_path/'.$voided->getName().'.xml'),$see->getFactory()->getLastXml());

        $sunatResponse = $sunat->sunatResponse($voided,$result);

        $pdf_path = "";
        if($sunatResponse['status'] == 1){
            $pdf_path = $sunat->generatePDF($invoice);
        }

        if($sunatResponse['status'] != 1){
            $this->dispatch('error', ['label' => 'No se puede emitir un comprobante en estos momentos por fallos con sunat, Intentarlo mas tarde.']);
            return;
        }

        $document->update(['code'=>"1"]);

    }

    public function delete($id)
    {
        $this->dispatch('document_delete', ['label' => 'Esta seguro que desea anular el comprobante?.', 'btn' => 'Anular', 'id' => $id]);
    }

    public
    function creditNote($id)
    {
        return redirect()->route('documents.credit-note', $id);
    }

    public
    function render()
    {
        $documents = Document::paginate(15);
        return view('livewire.documents.table-documents', compact('documents'));
    }
}
