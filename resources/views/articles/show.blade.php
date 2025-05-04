@extends('../themes/echo')

@section('subhead')
    <title>ShiperSales | Artículos | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:articles.edit-article :id="$id" />
@endsection
