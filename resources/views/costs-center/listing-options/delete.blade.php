<x-p-button
    color="danger"
    size="small"
    wire:click="$dispatch('openModal', {component: 'costs-center-delete', arguments: { costCenter: {{$center->id}} } })"
>
    Delete
</x-p-button>
