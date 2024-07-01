<x-p-paper>
    <x-slot:title>Cancel booking confirmation</x-slot:title>
    Are you sure you want to cancel courier booking ({{$booking->order_id}})?<br/>
    Courier was booked for {{$booking->pickup_from->format('d-m-Y')}} ({{$booking->pickup_from->format('H:i')}}-{{$booking->pickup_to->format('H:i')}})
    <div class="mt-2 text-right">
        <x-p-button color="success" wire:click="$dispatch('closeModal')">No</x-p-button>
        <x-p-button color="danger" wire:click="cancelBooking">
            Yes, remove booking
        </x-p-button>
    </div>
</x-p-paper>
