<x-p-paper>
    <x-slot:title>{{$title}}</x-slot:title>
    <form wire:submit="update">
        <div class="p-2">
            <x-p-input label="Name" wire:model.live.debounce="name"/>
            <div class="mt-4 text-right">
                <x-p-button
                    wire:loading.attr="disabled"
                    type="submit"
                    color="primary"
                    size="large"
                >
                    Save changes
                </x-p-button>
            </div>
        </div>
    </form>
</x-p-paper>
