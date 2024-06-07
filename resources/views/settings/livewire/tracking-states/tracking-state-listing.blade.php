<div>
    <x-p-paper>
        <x-slot:title>Events states</x-slot:title>
        <x-p-table>
            <x-p-thead>
                <x-p-tr left>
                    <x-p-th>Status symbol</x-p-th>
                    <x-p-th>Description</x-p-th>
                    <x-p-th>Typ</x-p-th>
                    <x-p-th right>Options</x-p-th>
                </x-p-tr>
            </x-p-thead>
            <x-p-tbody>
                @foreach($events as $event)
                    <x-p-tr>
                        <x-p-td class="font-bold text-sm {{$event->type?->getStateColor()}}">{{$event->code}}</x-p-td>
                        <x-p-td class="text-sm">{{$event->label}}</x-p-td>
                        <x-p-td>{{$event->type->getLabel()}}</x-p-td>
                        <x-p-td right>
                            <x-p-button
                                wire:click="$dispatch('openModal', { component: 'tracking-event-edit', arguments: { event: '{{$event->code}}' }}) " size="small"
                            >
                                Edit
                            </x-p-button>
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>
    </x-p-paper>

</div>
