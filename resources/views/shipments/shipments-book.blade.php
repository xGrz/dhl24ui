<x-p-paper>
    <x-slot:title>
        Courier booking for {{$shipment->number}}
    </x-slot:title>
    <x-slot:actions>
        <button wire:click="$dispatch('closeModal')" class="text-red-100 hover:text-white">
            <x-p::icons.close/>
        </button>
    </x-slot:actions>

    <form wire:submit="book" id="booking" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <div class="col-span-2">
            @include('dhl-ui::bookings.form-components.pickup-date')
        </div>
        @include('dhl-ui::bookings.form-components.pickup-from')
        @include('dhl-ui::bookings.form-components.pickup-to')
        <div class="col-span-2">
            @include('dhl-ui::bookings.form-components.pickup-info')
        </div>
    </form>
    <div class="text-right mt-2">
        <x-p-button color="danger" wire:click="$dispatch('closeModal')">
            Cancel
        </x-p-button>
        <x-p-button color="success" type="submit" form="booking">
            Book
        </x-p-button>
    </div>

</x-p-paper>
