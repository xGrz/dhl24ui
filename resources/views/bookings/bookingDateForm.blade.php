@php use Illuminate\Support\Carbon; @endphp
<x-p-paper>
    <x-slot:title>Courier booking details</x-slot:title>
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-2 max-w-6xl mx-auto">
        <div class="col-span-2 md:col-span-3">
            <x-p-select label="Pickup date" wire:model.live="pickupDate">
                @foreach($dateOptions as $pickupDateOption)
                    <option value="{{$pickupDateOption}}">
                        {{$pickupDateOption}} ({{Carbon::parse($pickupDateOption)->dayName}})
                    </option>
                @endforeach
            </x-p-select>
            @if (!Carbon::parse($pickupDate)->isToday())
                <div class="text-yellow-500">You book a courier with a future date</div>
            @endif
        </div>

        <x-p-select label="Pickup from" wire:model.live="pickupFrom" wire:key="{{$pickupDate.$pickupFrom}}">
            @foreach($pickupFromOptions as $key => $from)
                <option>{{$from}}</option>
            @endforeach
        </x-p-select>

        <x-p-select label="Pickup to" wire:model.live="pickupTo" wire:key="{{$pickupDate.$pickupFrom.$pickupTo}}">
            @foreach($pickupToOptions as $key => $to)
                <option>{{$to}}</option>
            @endforeach
        </x-p-select>

        <div class="col-span-full">
            <x-p-input label="Info" wire:model="info"/>
        </div>

    </div>
    <div class="text-center col-span-4 mt-4 mb-1">
        <x-p-button size="large" wire:click="book" disabled="{{!$postalCode}}">Book courier</x-p-button>
    </div>
</x-p-paper>
