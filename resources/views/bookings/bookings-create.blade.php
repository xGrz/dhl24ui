@php use Illuminate\Support\Carbon; @endphp
<div class="grid gap-2">
    <x-p-paper>
        <x-slot:title>Select shipments</x-slot:title>
        <x-p-table>
            <x-p-thead>
                <x-p-tr>
                    <x-p-th>
                        <x-p-checkbox wire:model.live="selectedAll"/>
                    </x-p-th>
                    <x-p-th left>Number</x-p-th>
                    <x-p-th left>Shipment date</x-p-th>
                    <x-p-th left>Shipper</x-p-th>
                    <x-p-th left>Receiver</x-p-th>
                    <x-p-th left>Type</x-p-th>
                    <x-p-th right>Items</x-p-th>
                </x-p-tr>
            </x-p-thead>
            <x-p-tbody>
                @foreach($shipments as $shipment)
                    <x-p-tr>
                        <x-p-td>
                            <x-p-checkbox wire:model.live="shipmentList" value="{{$shipment->number}}"/>
                        </x-p-td>
                        <x-p-td>{{$shipment->number}}</x-p-td>
                        <x-p-td>{{$shipment->shipment_date->format('d-m-Y')}}</x-p-td>
                        <x-p-td>{{$shipment->shipper_name}}</x-p-td>
                        <x-p-td>{{$shipment->receiver_name}}</x-p-td>
                        <x-p-td><x-dhl-ui::shipment-type :shipment="$shipment" /></x-p-td>
                        <x-p-td right>
                            {{$shipment->items->count()}}
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    </x-p-paper>
    @if($postalCode)
        <x-p-paper>
            <x-slot:title>Courier booking details</x-slot:title>
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-2 max-w-6xl mx-auto">
                <div class="col-span-2 md:col-span-3">
                    @include('dhl-ui::bookings.form-components.pickup-date')
                </div>

                @include('dhl-ui::bookings.form-components.pickup-from')
                @include('dhl-ui::bookings.form-components.pickup-to')

                <div class="col-span-full">
                    @include('dhl-ui::bookings.form-components.pickup-info')
                </div>

            </div>
            <div class="text-center col-span-4 mt-4 mb-1">
                <x-p-button size="large" wire:click="book" disabled="{{!$postalCode}}">Book courier</x-p-button>
            </div>
        </x-p-paper>

    @endif
    <div wire:loading>Loading...</div>
</div>
