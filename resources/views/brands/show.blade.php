@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Marcas | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:brands.edit-brand :id="$id" />
@endsection
