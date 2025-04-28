<?php

namespace App\Exports;

use App\Models\SaleDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportMonthExport implements
    FromQuery,
    WithHeadings,
    WithCustomStartCell,
    WithDrawings,
    WithEvents,
    ShouldAutoSize,
    WithMapping,
    WithColumnFormatting
{
    public function __construct(string $month,string $year,int $provider){
        $this->month = $month;
        $this->year = $year;
        $this->provider = $provider;
    }

    public function query()
    {
        return SaleDetail::query()
            ->select(
                'providers.name as provider_name',
                'categories.name as category_name',
                'articles.sku as article_sku',
                'brands.name as brand_name',
                'articles.title as article_name',
                'sale_details.quantity as quantity',
                'sale_details.price as sale_price',
                'articles.purchase_price as purchase_price',
                'clients.name as client_name',
                'contacts.name as contact_name',
                'districts.name as district_name',
                'departments.name as department_name',
                'provinces.name as province_name',
                'sale_details.created_at as created_at'
            )
            ->join('articles', 'sale_details.article_id', '=', 'articles.id')
            ->join('providers', 'articles.provider_id', '=', 'providers.id')
            ->join('brands', 'articles.brand_id', '=', 'brands.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('contacts', 'sales.contact_id', '=', 'contacts.id')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->join('districts','clients.district_id','=','districts.id')
            ->join('departments','clients.department_id','=','departments.id')
            ->join('provinces','clients.province_id','=','provinces.id')
            ->whereMonth('sale_details.created_at', $this->month)
            ->whereYear('sale_details.created_at', $this->year)
            ->where('articles.provider_id', $this->provider);
    }

    public function headings(): array
    {

        return [
            'PROVEEDOR',
            'CATEGORIA',
            'SKU',
            'MARCA',
            'NOMBRE',
            'CANTIDAD',
            'PVP',
            'EN DOLARES ($)',
            'COSTO ($)',
            'GANANCIA ($)',
            'CLIENTE',
            'CONTACTO',
            'DISTRITO',
            'PROVINCIA',
            'AÑO',
            'MES',
            'MAPA'

        ];
    }

    public function map($row): array
    {
        $pvp = $row->sale_price * $row->quantity;
        $in_dollars = $pvp / 3.90;
        $charge = $row->purchase_price * $row->quantity;
        $gain = $in_dollars - $charge;

        return [
            $row->provider_name,
            $row->category_name,
            $row->article_sku,
            $row->brand_name,
            $row->article_name,
            $row->quantity,
            $pvp,
            $in_dollars,
            $charge,
            $gain,
            $row->client_name,
            $row->contact_name,
            $row->district_name,
            $row->province_name,
            Carbon::parse($row->created_at)->format('Y'),
            ucfirst(Carbon::parse($row->created_at)->locale('es')->translatedFormat('F')),
            $row->department_name

        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('ShiperSale');
        $drawing->setPath(public_path('/images/logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A2');

        return $drawing;
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1) Insertar título al lado del logo
                $month = Carbon::createFromFormat('!m', $this->month)
                    ->locale('es')
                    ->isoFormat('MMMM');
                $titulo = "REPORTE PERSONALIZADO DESDE {$month} HASTA {$this->year}";
                $sheet->setCellValue('B2', $titulo);

                // 2) Fusionar B1 hasta, digamos, H1 para que el texto tenga espacio
                $sheet->mergeCells('B2:Q2');

                // 3) Estilos: negrita, tamaño de letra, alineación
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 24,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 4) Ajustar alto de la fila 1 (para que coincida con la altura del logo)
                $sheet->getRowDimension(2)->setRowHeight(80);
                $sheet->getRowDimension(4)->setRowHeight(40);

                // 5) Opcional: ajustar ancho de columnas para que encaje bien
                foreach (range('B', 'Q') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }


                // 1) Averiguamos la última fila con datos
                $highestRow = $sheet->getHighestRow(); // ej. “25”

                // 2) Averiguamos la última columna con datos (si también es dinámica)
                $highestColumn = $sheet->getHighestColumn(); // ej. “Q”

                // 3) Construimos el rango completo de datos,
                //    por ejemplo desde A4 (cabecera) hasta Q{última fila}
                $rango = "A4:{$highestColumn}{$highestRow}";

                $sheet->getStyle($rango)->applyFromArray([
                    'borders' => [
                        'outline' => [            // perímetro externo
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color'       => ['rgb' => '000000'],
                        ],
                        'inside' => [             // sólo líneas interiores
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);



                $sheet->getStyle('A4:Q4')->applyFromArray([
                    'font' => [
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ],
                        'bold' => true,
                        'size' => 14
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '374080'],
                    ]
                ]);


            },
        ];
    }
}
