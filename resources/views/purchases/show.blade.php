@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Compras | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:purchases.show-purchase :id="$id" />
@endsection
