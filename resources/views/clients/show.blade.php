@extends('../themes/echo')

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <livewire:clients.edit-client :id="$id" />
@endsection
