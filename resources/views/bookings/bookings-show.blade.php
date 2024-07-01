<div>
    <x-p-paper>
        <x-slot:title>Courier book: {{$booking->order_id}}</x-slot:title>
        Courier has been booked for {{$booking->pickup_from->format('d-m-Y')}}
        from {{$booking->pickup_from->format('H:i')}}
        to {{$booking->pickup_to->format('H:i')}}.
    </x-p-paper>

    <x-p-paper class="mt-2">
        <x-slot:title>
            Shipments in booking
        </x-slot:title>
        <x-p-table size="small">
            <x-p-tbody>
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th left>Number</x-p-th>
                        <x-p-th left>Status</x-p-th>
                        <x-p-th left>Receiver name</x-p-th>
                        <x-p-th left>Receiver city</x-p-th>
                        <x-p-th left>Items</x-p-th>
                        <x-p-th left>Type</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                @foreach($booking->shipments as $shipment)
                    <x-p-tr>
                        <x-p-td>
                            <x-p-link href="{{route('dhl24.shipments.show', $shipment->id)}}">
                                {{$shipment->number}}
                            </x-p-link>
                        </x-p-td>
                        <x-p-td>
                            <x-dhl-ui::shipment-state :status="$shipment->tracking->first()"/>
                            <span class="text-sm block">
                                    {{$shipment->tracking->first()?->pivot->event_timestamp->format('d-m-Y H:i')}}
                                </span>
                        </x-p-td>
                        <x-p-td>{{$shipment->receiver_name}}</x-p-td>
                        <x-p-td>{{$shipment->receiver_city}}</x-p-td>
                        <x-p-td>{{$shipment->items->count()}}</x-p-td>
                        <x-p-td>
                            <x-dhl-ui::shipment-type :shipment="$shipment"/>
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    </x-p-paper>
    <div class="mt-4 text-right">
        <x-p-button color="danger">Cancel booking</x-p-button>
    </div>
</div>
