@extends('../themes/echo')

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <livewire:vouchers.edit-voucher :id="$id" />
@endsection
