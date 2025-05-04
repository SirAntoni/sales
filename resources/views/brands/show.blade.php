@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Marcas | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:brands.edit-brand :id="$id" />
@endsection
