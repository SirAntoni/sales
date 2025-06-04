<?php

namespace App\Services;

use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use Illuminate\Support\Facades\Log;
use Greenter\Model\Response\SummaryResult;

class SunatService
{
    public function getSee()
    {
        $see = new See();

        Log::info("PATH CERTIFICATE: " .  config('sunat.path_certificate'));
        Log::info("SUNAT RUC SOL: " .  config('sunat.ruc_sol'));
        Log::info("RUC: : " .  config('sunat.ruc'));

        ///$pfx = file_get_contents(storage_path(config('sunat.path_certificate')));
        //$password = 'zGwEPRvP6QeV26R';
        //$certificate = new X509Certificate($pfx, $password);
        //$see->setCertificate($certificate->export(X509ContentType::PEM));

        $see->setCertificate(file_get_contents(storage_path(config('sunat.path_certificate'))));
        $see->setService((env('APP_ENV') == "production") ? SunatEndpoints::FE_PRODUCCION : SunatEndpoints::FE_BETA);
        $see->setClaveSOL(config('sunat.ruc_sol'), config('sunat.usuario_sol'), config('sunat.clave_sol'));
        return $see;

    }

    public function getInvoice($data)
    {

        return (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc($data['tipoDoc']) // Factura - Catalog. 01
            ->setSerie($data['serie'])
            ->setCorrelativo($data['correlative'])
            ->setFechaEmision(new DateTime($data['date'])) // Zona horaria: Lima
            ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setCompany($this->getCompany())
            ->setClient($this->getClient($data['client']))
            ->setMtoOperGravadas($data['subtotal'])
            ->setMtoIGV($data['igv'])
            ->setTotalImpuestos($data['igv'])
            ->setValorVenta($data['subtotal'])
            ->setSubTotal($data['total'])
            ->setMtoImpVenta($data['total'])
            ->setDetails($this->getDetails($data['items']))
            ->setLegends([$this->getLegends($data['legend'])]);
    }


    public function getVoided($data){
        return (new Voided)
            ->setCorrelativo($data['correlative'])
            ->setFecGeneracion(new DateTime($datap['date']))
            ->setFecComunicacion(new DateTime())
            ->setCompany($this->getCompany())
            ->setDetails($this->getVoidedDetails($data['details']));
    }

    public function getVoidedDetails($details = [])
    {
        $greenDetails = [];

        foreach($details as $detail) {
            $greenDetails[] = (new VoidedDetail)
                ->setTipoDoc($detail['tipoDoc'])
                ->setSerie($detail['serie'])
                ->setCorrelativo($detail['correlative'])
                ->setDesMotivoBaja($detail['motivoBaja']);
        }
        return $greenDetails;
    }

    public function getClient($client)
    {

        return (new Client())
            ->setTipoDoc($client['tipoDoc'])
            ->setNumDoc($client['numDoc'])
            ->setRznSocial($client['name']);
    }

    public function getCompany()
    {
        return (new Company())
            ->setRuc(config('sunat.ruc'))
            ->setRazonSocial(config('sunat.razon_social'))
            ->setNombreComercial(config('sunat.nombre_comercial'))
            ->setAddress($this->getAddress());
    }

    public function getAddress()
    {
        return (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('SANTIAGO DE SURCO')
            ->setUrbanizacion('-')
            ->setDireccion('CAL. ARTESANOS 150 URB. ALBORADA INT. 205 C.C LAS PLAZUELAS')
            ->setCodLocal('0000');
    }

    public function getDetails($details = [])
    {
        $greenDetails = [];

        foreach($details as $detail) {
            $totalIgv = (float)$detail['total'] * 0.18;

            $greenDetails[] = (new SaleDetail())
                ->setCodProducto($detail['sku'])
                ->setUnidad('NIU') // Unidad - Catalog. 03
                ->setCantidad($detail['quantity'])
                ->setMtoValorUnitario($detail['price'])
                ->setDescripcion($detail['title'])
                ->setMtoBaseIgv($detail['total'])
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($totalIgv)
                ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                ->setTotalImpuestos($totalIgv) // Suma de impuestos en el detalle
                ->setMtoValorVenta($detail['total'])
                ->setMtoPrecioUnitario(((float)$detail['price'] * 0.18) + (float)$detail['price']);
        }
        return $greenDetails;
    }

    public function getLegends($legend){

        $greenLegend =  (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($legend);
        return $greenLegend;
    }

    public function sunatResponse($invoice,$result,$type="invoice"){

        $response = [];

        if (!$result->isSuccess()) {
            Log::info("--- START: Log comprobante " . $invoice->getName() . " ---");
            Log::info("FACTURA ERROR: " . $result->getError()->getCode());
            Log::info("MENSAJE ERROR: " . $result->getError()->getMessage());
            Log::info("--- END: log comprobante " . $invoice->getName() . " ---");
            $response['status'] = 0;
            $response['code'] = "000";
            $response['cdr'] = null;
            return $response;
        }

        if($type != "invoice"){
            $see = $this->getSee();
            $ticket = $result->getTicket();
            $result = $see->getStatus($ticket);
            if (!$result->isSuccess()) {
                Log::info("ERROR AL ANULAR COMPROBANTE: " . json_encode($result->getError()));
                $response['status'] = 0;
                $response['code'] = "000";
                $response['cdr'] = null;
                return $response;
            }
        }

        $cdr = $result->getCdrResponse();

        if($type="invoice"){
            file_put_contents(storage_path('/cdr_path/R-'.$invoice->getName().'.zip'), $result->getCdrZip());
        }else{
            file_put_contents(storage_path('/cdr_path_anulled/R-'.$invoice->getName().'.zip'), $result->getCdrZip());
        }



        $code = (int)$cdr->getCode();

        Log::info("CODE: " . $code);

        if ($code === 0) {
            if (count($cdr->getNotes()) > 0){
                Log::info("--- START: Log comprobante notes " . $invoice->getName() . " ---");
                Log::info($cdr->getNotes());
                Log::info("--- END: Log comprobante notes " . $invoice->getName() . " ---");
            }
            $response['status'] = 1;
            $response['code'] = $code;
            $response['cdr'] = '/cdr_path/R-'.$invoice->getName().'.zip';
            $response['notes'] = (count($cdr->getNotes()) > 0) ? $cdr->getNotes():[];
            Log::info("--- START: Log comprobante 0 " . json_encode($response) . " ---");
        } else if ($code >= 2000 && $code <= 3999) {
            Log::info("--- START: Log comprobante >= 2000 && <= 3999 " . $invoice->getName() . " ---");
            Log::info("Code: " . $code);
            Log::info($cdr->getNotes());
            Log::info("--- END: Log comprobante >= 2000 && <= 3999 " . $invoice->getName() . " ---");
            $response['status'] = 2;
            $response['code'] = $code;
            $response['cdr'] = '/cdr_path/R-'.$invoice->getName().'.zip';
            $response['notes'] = (count($cdr->getNotes()) > 0) ? $cdr->getNotes():[];
        } else {
            Log::info("--- START: Log comprobante ELSE " . $invoice->getName() . " ---");
            Log::info("Code: " . $code);
            Log::info($cdr->getNotes());
            Log::info("--- END: Log comprobante ELSE " . $invoice->getName() . " ---");
            $response['status'] = 0;
        }
        return $response;
    }

    public function generatePdf($invoice,$type='invoice'){
        $htmlReport = new HtmlReport();

        $resolver = new DefaultTemplateResolver();
        $htmlReport->setTemplate($resolver->getTemplate($invoice));

        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);

        $report->setBinPath(config('wkhtmltopdf.bin_path'));
        $params = [
            'system' => [
                'logo' => file_get_contents(public_path('/images/logo_invoice.png')), // Logo de Empresa
                'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
            ],
            'user' => [
                'header'     => 'Telf: <b>+51959140757</b>', // Texto que se ubica debajo de la dirección de empresa
                'extras'     => [
                    // Leyendas adicionales
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'     ],
                    ['name' => 'VENDEDOR'         , 'value' => 'LOPEZ VERASTEGUI RAUL EDUARDO'],
                ]
            ]
        ];

        $pdf = $report->render($invoice, $params);

        if($type == "invoice"){
            file_put_contents(storage_path('/pdf_path/'.$invoice->getName().'.pdf'),$pdf);
            return '/pdf_path/'.$invoice->getName().'.pdf';
        }else{
            file_put_contents(storage_path('/pdf_path/'.$invoice->getName().'.pdf'),$pdf);
            return '/pdf_path_anulled/'.$invoice->getName().'.pdf';
        }



    }

}
