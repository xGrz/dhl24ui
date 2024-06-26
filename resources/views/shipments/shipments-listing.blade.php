<div wire:poll.15s>
    <x-p-pagination livewire :source="$shipments"/>
    <x-p-paper>
        <x-slot:title>ShipmentDraft list</x-slot:title>
        <x-slot:actions>
{{--            <x-p-button href="{{ route('dhl24.shipments.create') }}" color="info">Create</x-p-button>--}}
            <x-p-button href="{{ route('dhl24.bookings.create') }}" color="warning">Book</x-p-button>
            <x-p-button color="success">Report</x-p-button>
        </x-slot:actions>
        @if($shipments)
            <x-p-table>
                <x-p-thead>
                    <x-p-tr left>
                        <x-p-th>Number</x-p-th>
                        <x-p-th>State</x-p-th>
                        <x-p-th>Sender</x-p-th>
                        <x-p-th>Receiver</x-p-th>
                        <x-p-th left>Items</x-p-th>
                        <x-p-th>Type/Booking</x-p-th>
                        <x-p-th right>COD</x-p-th>
                        <x-p-th right>Options</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($shipments as $shipment)
                        <x-p-tr>
                            <x-p-td>
                                <span class="block text-sm">
                                    {{ $shipment->shipment_date->format('d-m-Y') }}
                                </span>
{{--                                <x-p-link href="{{route('dhl24.shipments.show', $shipment->id)}}">--}}
                                    {{ $shipment->number }}
{{--                                </x-p-link>--}}
                            </x-p-td>
                            <x-p-td>
                                <x-dhl-ui::shipment-state :status="$shipment->tracking->first()"/>
                            </x-p-td>
                            <x-p-td class="text-sm">
                                {{ $shipment->shipper_name }}
                            </x-p-td>
                            <x-p-td class="text-sm">
                                {{ $shipment->receiver_name }}<br/>
                                {{ postalCode($shipment->receiver_postal_code, $shipment->receiver_country) }} {{ $shipment->receiver_city }}
                            </x-p-td>
                            <x-p-td left>{{ $shipment->items->count() }}</x-p-td>
                            <x-p-td>
                                <x-dhl-ui::shipment-type :shipment="$shipment"/>
                                @if($shipment->courier_booking)
                                    <x-p-link class="text-sm"
                                              href="{{route('dhl24.bookings.show', $shipment->courier_booking->id)}}">
                                        {{ $shipment->courier_booking?->order_id }}
                                    </x-p-link>
                                @endif
                            </x-p-td>
                            <x-p-td right>
                                @if($shipment->collect_on_delivery)
                                    {{ money($shipment->collect_on_delivery) }}
                                @endif
                            </x-p-td>
                            <x-p-td right>
{{--                                @if (!$shipment->courier_booking_id)--}}
{{--                                    <x-p-button href="{{ route('dhl24.shipments.booking.create', $shipment->id) }}"--}}
{{--                                                size="small">--}}
{{--                                        Book--}}
{{--                                    </x-p-button>--}}
{{--                                @endif--}}
{{--                                <x-p-button href="{{ route('dhl24.shipments.label', $shipment->id) }}" size="small">--}}
{{--                                    Label--}}
{{--                                </x-p-button>--}}
{{--                                <x-p-button href="{{ route('dhl24.shipments.destroy', $shipment->id) }}" size="small"--}}
{{--                                            color="danger">--}}
{{--                                    Delete--}}
{{--                                </x-p-button>--}}
                            </x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>

            <div class="py-3">
                <x-p-pagination livewire :source="$shipments"/>
            </div>
        @else
            <x-p-not-found message="Transactions for found."/>
        @endif

    </x-p-paper>
</div>
