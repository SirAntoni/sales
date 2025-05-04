@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Compras anuladas | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:canceled.table-canceled-purchases/>
@endsection
