@php use Carbon\Carbon; @endphp
<x-p-paper>
    <x-slot:title>Shipments for <span class="text-white">{{$costCenter->name}}</span></x-slot:title>

    <x-p-pagination livewire :source="$costCenter->shipments"></x-p-pagination>

    <div class="text-right">
        <x-p-datepicker wire:model.live="from" label="From date" :max="now()"/>
        <x-p-datepicker wire:model.live="to" label="To date" :min="$from" :max="now()"/>
    </div>


    <x-p-table>
        <x-p-thead>
            <x-p-tr>
                <x-p-th left>Date</x-p-th>
                <x-p-th left>Number</x-p-th>
                <x-p-th left>Receiver</x-p-th>
                <x-p-th left>Status</x-p-th>
                <x-p-th right>Cost</x-p-th>
            </x-p-tr>
        </x-p-thead>
        <x-p-tbody>
            @foreach($costCenter->shipments as $shipment)
                <x-p-tr>
                    <x-p-td>{{$shipment->shipment_date->format('d-m-Y')}}</x-p-td>
                    <x-p-td>
                        <x-p-link href="{{route('dhl24.shipments.show', $shipment->id)}}">
                            {{$shipment->number}}
                        </x-p-link>
                        @if($shipment->items_count > 1)
                            ({{$shipment->items_count}})
                        @endif
                    </x-p-td>
                    <x-p-td>
                        {{$shipment->receiver_name}}
                    </x-p-td>
                    <x-p-td>
                        <x-dhl-ui::shipment-state :status="$shipment->tracking->first()"/>
                    </x-p-td>
                    <x-p-td right>{{$shipment->cost}}</x-p-td>
                </x-p-tr>
            @endforeach
        </x-p-tbody>
        <x-p-tfoot>
            <x-p-tr>
                <x-p-td right class="!py-0" colspan="4">Page total:</x-p-td>
                <x-p-td right class="!py-0">{{$costCenter->shipments->sum(fn($item) => $item->cost)}}</x-p-td>
            </x-p-tr>
            <x-p-tr>
                <x-p-td right class="!py-0" colspan="4">Total selected in period:</x-p-td>
                <x-p-td right class="!py-0 text-white font-bold">{{$periodSum}}</x-p-td>
            </x-p-tr>
        </x-p-tfoot>
    </x-p-table>
</x-p-paper>
