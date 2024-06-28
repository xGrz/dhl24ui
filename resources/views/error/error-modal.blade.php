<x-p-paper class="text-white">
    <x-slot:title class="!bg-red-700">DHL ERROR: {{$title}}</x-slot:title>
    <x-slot:actions>
        <button wire:click="close" class="text-red-200 hover:text-white">
            <x-p::icons.close />
        </button>
    </x-slot:actions>
    <div class="text-lg p-2">
        {{ $message }}
    </div>

    <div class="mt-2 text-right">
        <x-p-button  wire:click="close">Close</x-p-button>
    </div>
</x-p-paper>
