<x-p-paper>

    <x-slot:title>Costs centers</x-slot:title>
    <x-slot:actions>
        <x-p-button wire:click="$dispatch('openModal', {component: 'costs-center-create'})">New</x-p-button>
    </x-slot:actions>

    <x-p-pagination livewire :source="$costsCenters"/>

    <x-p-table>
        <x-p-thead>
            <x-p-tr>
                <x-p-th left>Name</x-p-th>
                <x-p-th right>Shipments</x-p-th>
                <x-p-th right>Packages</x-p-th>
                <x-p-th right>Packages/Shipment</x-p-th>
                <x-p-th right>Avg. shipment cost</x-p-th>
                <x-p-th right>Avg. package cost</x-p-th>
                <x-p-th right>Total cost</x-p-th>
                <x-p-th right>Options</x-p-th>
            </x-p-tr>
        </x-p-thead>
        <x-p-tbody>
            @foreach($costsCenters as $center)
                <x-p-tr>
                    <x-p-td>
                        @if(!$center->deleted_at)
                            <x-p-link href="{{route('dhl24.costs-center.show', $center->id)}}">
                                {{$center->name}}
                            </x-p-link>
                        @else
                            <x-p-link
                                href="{{route('dhl24.costs-center.show', $center->id)}}"
                                class="!text-slate-400 hover:!text-slate-200 line-through"
                            >
                                {{$center->name}}
                            </x-p-link>
                        @endif
                    </x-p-td>
                    <x-p-td right>{{$center->shipments_count}}</x-p-td>
                    <x-p-td right>{{$center->shipment_items_count}}</x-p-td>
                    <x-p-td right>{{money($center->shipment_items_avg_quantity)}}</x-p-td>
                    <x-p-td right>{{money($center->shipments_avg_cost)}}</x-p-td>
                    <x-p-td right>
                        {{money($center->shipments_sum_cost / ($center->shipment_items_count ?: 1)) }}
                    </x-p-td>
                    <x-p-td right>{{money($center->shipments_sum_cost)}}</x-p-td>
                    <x-p-td right>
                        @if(!$center->deleted_at)
                            @include('dhl-ui::costs-center.listing-options.default')
                            @include('dhl-ui::costs-center.listing-options.edit')
                            @include('dhl-ui::costs-center.listing-options.delete')
                        @else
                            @include('dhl-ui::costs-center.listing-options.restore')
                        @endif
                    </x-p-td>
                </x-p-tr>
            @endforeach
        </x-p-tbody>
    </x-p-table>
</x-p-paper>
