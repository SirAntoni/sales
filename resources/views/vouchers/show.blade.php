@extends('../themes/echo')

@section('subhead')
    <title>WariFact | Comprobantes | Sistema de ventas</title>
@endsection

@section('subcontent')
    <livewire:vouchers.edit-voucher :id="$id" />
@endsection
