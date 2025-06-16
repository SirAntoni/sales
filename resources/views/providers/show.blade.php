@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Proveedores | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:providers.edit-provider :id="$id" />
@endsection
