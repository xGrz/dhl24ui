<div class="grid grid-cols-1 gap-2">
    <x-p-paper>
        <x-p-select label="Pickup date" wire:model.live="pickupDate">
            @foreach($dateOptions as $pickupDateOption)
                <option>{{$pickupDateOption}}</option>
            @endforeach
        </x-p-select>
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
