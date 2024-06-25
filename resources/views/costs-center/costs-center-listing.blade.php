<x-p-paper>

    <x-slot:title>Costs centers</x-slot:title>

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
                        <x-p-link href="{{route('dhl24.costs-center.show', $center->id)}}">
                            {{$center->name}}
                        </x-p-link>
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
                        @if($center->is_default)
                            <button type="button" class="text-yellow-500" disabled>
                                <x-p::icons.star-full class="w-5 h-5"/>
                            </button>
                        @else
                            <button href="#" wire:click.prevent="setAsDefault({{$center->id}})"
                                    class="text-slate-500 hover:text-yellow-500 transition-all">
                                <x-p::icons.star class="w-5 h-5"/>
                            </button>
                        @endif
                        <x-p-button
                            type="button"
                            size="small"
                            wire:click="$dispatch('openModal', {component: 'cost-center-edit', arguments: { costCenter: {{$center}} } })"
                        >
                            Edit
                        </x-p-button>

                        <x-p-button
                            color="danger"
                            size="small"
                            wire:click="$dispatch('openModal', {component: 'cost-center-delete', arguments: { costCenter: {{$center}} } })"
                        >
                            Delete
                        </x-p-button>                    </x-p-td>
                </x-p-tr>
            @endforeach
        </x-p-tbody>
    </x-p-table>

</x-p-paper>
