@extends('p::app')

@section('content')

    <x-p-paper>
        <x-slot:title>Select shipments</x-slot:title>
        <x-p-table>
            @foreach($shipments as $shipment)
                <x-p-tr>
                    <x-p-td>
                        <x-p-checkbox></x-p-checkbox>
                    </x-p-td>
                    <x-p-td>{{$shipment->number}}</x-p-td>
                    <x-p-td>{{$shipment->shipper_name}}</x-p-td>
                    <x-p-td>{{$shipment->receiver_name}}</x-p-td>
                    <x-p-td>{{$shipment->items->count()}}</x-p-td>
                </x-p-tr>
            @endforeach
        </x-p-table>
        <div class="text-right">
            <x-p-button>Zamów</x-p-button>
        </div>
    </x-p-paper>

    <x-p-paper>
        <x-slot:title>Select date</x-slot:title>
    </x-p-paper>

@endsection
