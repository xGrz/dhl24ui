<div>
    <x-p-paper>
        <x-slot:title>Select shipments</x-slot:title>
        <x-p-table>
            <x-p-thead>
                <x-p-tr>
                    <x-p-th>
                        <x-p-checkbox wire:model.live="selectedAll"/>
                    </x-p-th>
                    <x-p-th left>Number</x-p-th>
                    <x-p-th left>Shipper</x-p-th>
                    <x-p-th left>Receiver</x-p-th>
                    <x-p-th right>Number of packages</x-p-th>
                </x-p-tr>
            </x-p-thead>
            <x-p-tbody>
                @foreach($shipments as $shipment)
                    <x-p-tr>
                        <x-p-td>
                            <x-p-checkbox wire:model.live="shipmentList" value="{{$shipment->number}}"/>
                        </x-p-td>
                        <x-p-td>{{$shipment->number}}</x-p-td>
                        <x-p-td>{{$shipment->shipper_name}}</x-p-td>
                        <x-p-td>{{$shipment->receiver_name}}</x-p-td>
                        <x-p-td right>{{$shipment->items->count()}}</x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
        <div class="text-center mt-4 mb-2">
            <x-p-button disabled="{{!$bookingEnabled}}" wire:click="book">Book courier</x-p-button>
        </div>
    </x-p-paper>
</div>
