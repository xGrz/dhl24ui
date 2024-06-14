@extends('p::app')

@section('content')
    @livewire('create-single-shipment-booking', [
        'shipment' => $shipment,
        'dateOptions' => $dateOptions
    ])
@endsection
