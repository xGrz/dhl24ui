@extends('p::app')

@section('content')
    @livewire('costs-center-details', ['costCenter' => $costCenter])
@endsection
