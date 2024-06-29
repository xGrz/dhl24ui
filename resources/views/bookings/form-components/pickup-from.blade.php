<x-p-select label="Pickup from" wire:model.live="pickupFrom" wire:key="{{$pickupDate.$pickupFrom}}">
    @foreach($pickupFromOptions as $key => $from)
        <option>{{$from}}</option>
    @endforeach
</x-p-select>
