@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Usuarios | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:users.edit-user :id="$id" />
@endsection
