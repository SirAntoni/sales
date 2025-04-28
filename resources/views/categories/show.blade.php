@extends('../themes/echo')

@section('subhead')
    <title>Tailwise - Admin Dashboard Template</title>
@endsection

@section('subcontent')
    <livewire:categories.edit-category :id="$id" />
@endsection
