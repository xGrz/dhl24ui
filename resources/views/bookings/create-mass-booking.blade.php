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
                        <x-p-td>
                            @if($shipment->isExpress())
                                <span class="text-green-600">Express</span>
                            @else
                                <span class="text-amber-600">Pallet</span>
                            @endif
                        </x-p-td>
                        <x-p-td right>
                            {{$shipment->items->count()}}
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    </x-p-paper>
    @if($postalCode)
        <div class="grid grid-cols-4 gap-2">
            <x-p-paper class="col-span-2">
                <x-p-select label="Pickup date" wire:model.live="pickupDate">
                    @foreach($dateOptions as $pickupDateOption)
                        <option value="{{$pickupDateOption}}">
                            {{$pickupDateOption}} ({{Carbon::parse($pickupDateOption)->dayName}})
                        </option>
                    @endforeach
                </x-p-select>
                @if (!Carbon::parse($pickupDate)->isToday())
                    <div class="text-yellow-500">You book a courier with a future date</div>
                @endif
            </x-p-paper>

            <x-p-paper>
                <x-p-select label="Pickup from" wire:model.live="pickupFrom" wire:key="{{$pickupDate.$pickupFrom}}">
                    @foreach($pickupFromOptions as $key => $from)
                        <option>{{$from}}</option>
                    @endforeach
                </x-p-select>
            </x-p-paper>

            <x-p-paper>
                <x-p-select label="Pickup to" wire:model.live="pickupTo" wire:key="{{$pickupDate.$pickupFrom.$pickupTo}}">
                    @foreach($pickupToOptions as $key => $to)
                        <option>{{$to}}</option>
                    @endforeach
                </x-p-select>
            </x-p-paper>
            <x-p-paper class="col-span-4">
                <x-p-input label="Info" wire:model="info" />
            </x-p-paper>

            <div class="text-center col-span-4">
                <x-p-button size="large" wire:click="book" disabled="{{!$postalCode}}">Book</x-p-button>
            </div>
        </div>
    @endif
    <div wire:loading>Loading...</div>

</div>
