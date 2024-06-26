<x-p-button
    type="button"
    size="small"
    wire:click="$dispatch('openModal', {component: 'costs-center-edit', arguments: { costCenter: {{$center->id}} } })"
>
    Edit
</x-p-button>
