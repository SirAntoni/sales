@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Notas de crédito | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:documents.credit-note :id="$id" />
@endsection
