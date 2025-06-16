@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Ventas | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:sales.show-sale :id="$id" />
@endsection
