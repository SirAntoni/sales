@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Categorías | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:categories.edit-category :id="$id" />
@endsection
