@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Proveedores | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:providers.edit-provider :id="$id" />
@endsection
