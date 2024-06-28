<x-p-paper>
    <x-slot:title>
        Shipment ({{$shipment->number}}) delete confirmation
    </x-slot:title>
    <div class="text-orange-500 text-lg">
        Are you sure you want to delete this shipment?
    </div>
    <div>{{$shipment->receiver_name}}</div>
    <div>
        {{$shipment->receiver_country}} {{postalCode($shipment->receiver_postal_code, $shipment->receiver_country)}} {{ $shipment->receiver_city }}
    </div>
    <div>
        {{$shipment->receiver_street}} {{$shipment->receiver_house_number}}
    </div>
    <div class="text-right mt-2">
        <x-p-button color="success" wire:click="cancel">
            Cancel
        </x-p-button>
        <x-p-button color="danger" wire:click="confirmed">
            Yes, delete shipment now!
        </x-p-button>
    </div>
</x-p-paper>
