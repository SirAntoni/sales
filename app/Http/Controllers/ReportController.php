<?php

namespace App\Http\Controllers;

use App\Exports\ReportDayliExport;
use App\Exports\ReportCustomExport;
use App\Exports\ReportMonthExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(){
        return view('reports.index');
    }

    public function dayli(Request $request){

        $request->validate([
            'date' => 'required',
        ]);

        return Excel::download(new ReportDayliExport($request->query('date')), 'reporte-diario-'. $request->query('date') . '.xlsx');
    }

    public function custom(Request $request){

        $request->validate([
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        return Excel::download(new ReportCustomExport($startDate,$endDate), 'reporte-custom-'. $startDate . '-'.$endDate .'.xlsx');
    }

    public function month(Request $request){

        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'provider' =>  'required'
        ]);

        $month = $request->query('month');
        $monthName = Carbon::createFromFormat('!m', $month)
            ->locale('es')
            ->isoFormat('MMMM');
        $year = $request->query('year');
        $provider = $request->query('provider');

        return Excel::download(new ReportMonthExport($month,$year,$provider), 'reporte-mensual-'. $monthName . '-'.$year .'.xlsx');
    }
}
