@php use Illuminate\Support\Carbon; @endphp
<div class="grid grid-cols-1 gap-2">
    <x-p-paper>
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
    </x-p-paper>

    <x-p-paper>
        <x-p-select label="Pickup from" wire:model.live="pickupFrom" wire:key="{{$pickupDate.$pickupFrom}}">
            @foreach($pickupFromOptions as $key => $from)
                <option>{{$from}}</option>
            @endforeach
        </x-p-select>
    </x-p-paper>

    <x-p-paper>
        <x-p-select label="Pickup to" wire:model.live="pickupTo" wire:key="{{$pickupDate.$pickupFrom.$pickupTo}}">
            @foreach($pickupToOptions as $key => $to)
                <option>{{$to}}</option>
            @endforeach
        </x-p-select>
    </x-p-paper>

    <div class="text-center">
        <x-p-button size="large" wire:click="book">Book</x-p-button>
    </div>
</div>