<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Services\SunatService;
use App\Services\UtilSunat;
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

        if($document->document_type == 2){
            $document->update([
                'status' => 'anulado',
                'status_sunat' => 'anulado'
            ]);

            return;
        }

        $sunat = new SunatService();

        $util = UtilSunat::getInstance();
        $sunat = new SunatService();


        $anuladosCount = Document::where('status', 'anulado')->count();
        $nextCorrelativo = $anuladosCount + 1;

        $data = [
            "correlative" => $nextCorrelativo,
            "details" => [
                [
                    "tipoDoc" => ($document->document_type == '1') ? '01' : '03',
                    "serie" => $document->serie,
                    "correlative" => $document->correlative,
                    "motivoBaja" => 'PRUEBAS DE INTEGRACIÓN',
                ]
            ]
        ];

        $voided = $sunat->getVoided($data);

        $see = $sunat->getSee();

        $result = $see->send($voided);

        file_put_contents(storage_path('/xml_path_anulled/' . $voided->getName() . '.xml'), $see->getFactory()->getLastXml());

        $sunatResponse = $sunat->sunatResponse($voided, $result, "voided");

        $pdf_path = "";
        if ($sunatResponse['status'] == 1) {
            $pdf_path = $sunat->generatePDF($invoice);
        }

        if ($sunatResponse['status'] != 1) {
            $this->dispatch('error', ['label' => 'No se puede emitir un comprobante en estos momentos por fallos con sunat, Intentarlo mas tarde.']);
            return;
        }

        $document->update(
            [
                'status' => "anulado",
                'xml_path_anulled' => '/xml_path/' . $voided->getName() . '.xml',
                'cdr_path_anulled' => $sunatResponse['cdr'] ?? '',
                'pdf_path_anulled' => $pdf_path,
            ]
        );

        return;

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
