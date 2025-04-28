@extends('../themes/echo')

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <livewire:sales.show-sale :id="$id" />
@endsection
