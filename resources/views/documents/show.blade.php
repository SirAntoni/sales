@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Nueva Nota de crédito | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:documents.new-document :id="$id" />
@endsection
