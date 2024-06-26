<x-p-button
    color="warning"
    size="small"
    wire:click="$dispatch('openModal', {component: 'costs-center-restore', arguments: { costCenterId: {{$center->id}} } })"
>
    Restore
</x-p-button>
