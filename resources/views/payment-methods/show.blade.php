@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Métodos de pago | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:payment-methods.edit-payment-method :id="$id" />
@endsection
