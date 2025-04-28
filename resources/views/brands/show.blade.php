@extends('../themes/echo')

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <livewire:brands.edit-brand :id="$id" />
@endsection
