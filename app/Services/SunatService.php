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
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\Facades\Log;

class SunatService
{
    public function getSee()
    {
        $see = new See();
        $see->setCertificate(file_get_contents(storage_path('/cert/certificate.pem')));
        $see->setService(SunatEndpoints::FE_BETA); //FE_PRODUCCION
        $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');

        return $see;
    }

    public function getInvoice($data)
    {

        return (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01
            ->setSerie("F001")
            ->setCorrelativo("617")
            ->setFechaEmision(new DateTime('2020-08-24 13:05:00-05:00')) // Zona horaria: Lima
            ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setCompany($this->getCompany())
            ->setClient($this->getClient())
            ->setMtoOperGravadas(100.00)
            ->setMtoIGV(18.00)
            ->setTotalImpuestos(18.00)
            ->setValorVenta(100.00)
            ->setSubTotal(118.00)
            ->setMtoImpVenta(118.00)
            ->setDetails($this->getDetails())
            ->setLegends($this->getLegends());
    }

    public function getClient()
    {
        return (new Client())
            ->setTipoDoc('6')
            ->setNumDoc('20000000001')
            ->setRznSocial('EMPRESA X');
    }

    public function getCompany()
    {
        return (new Company())
            ->setRuc("20123456789")
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($this->getAddress());
    }

    public function getAddress()
    {
        return (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000');
    }

    public function getDetails()
    {
        $item = (new SaleDetail())
            ->setCodProducto('P001')
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setCantidad(2)
            ->setMtoValorUnitario(50.00)
            ->setDescripcion('PRODUCTO 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv(18.00)
            ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
            ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
            ->setMtoValorVenta(100.00)
            ->setMtoPrecioUnitario(59.00);

        return [$item];

    }

    public function getLegends(){
        $legend =  (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        return [$legend];
    }

    public function sunatResponse($invoice,$result){

        $response = [];
        Log::info("FACTURA RESPUESTA: LOG");
        if (!$result->isSuccess()) {
            Log::info("FACTURA ERROR: " . $result->getError()->getCode());
            Log::info("FACTURA ERROR: " . $result->getError()->getCode());
            Log::info("MENSAJE ERROR: " . $result->getError()->getMessage());
            $response['status'] = 0;
            $response['cdr'] = null;
            return $response;
        }

        $cdr = $result->getCdrResponse();

        file_put_contents(storage_path('/cdr_path/R-'.$invoice->getName().'.zip'), $result->getCdrZip());

        $code = (int)$cdr->getCode();

        if ($code === 0) {
            if (count($cdr->getNotes()) > 0)  Log::info($cdr->getNotes());
            $response['status'] = 1;
            $response['cdr'] = '/cdr_path/R-'.$invoice->getName().'.zip';
        } else if ($code >= 2000 && $code <= 3999) {
            $response['status'] = 2;
            $response['cdr'] = '/cdr_path/R-'.$invoice->getName().'.zip';
        } else {
            $response['status'] = 0;
            $response['cdr'] = '/cdr_path/R-'.$invoice->getName().'.zip';
        }

        return $response;

    }


}
