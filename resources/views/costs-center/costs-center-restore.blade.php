<x-p-paper class="bg-slate-800">
    <x-slot:title>Restore confirmation required</x-slot:title>
    <div class="p-2">
        Do you want to restore <strong class="text-white">{{ $costCenter->name }}</strong> cost center?
    </div>
    <div class="p-2 text-right">
        <x-p-button wire:click="cancel" type="button" color="success" size="">Cancel</x-p-button>
        <x-p-button wire:click="restoreConfirmed" type="button" color="warning" size="">Yes, I want to restore it.</x-p-button>
    </div>
</x-p-paper>
