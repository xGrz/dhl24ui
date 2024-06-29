<x-p-select label="Pickup to" wire:model.live="pickupTo" wire:key="{{$pickupDate.$pickupFrom.$pickupTo}}">
    @foreach($pickupToOptions as $key => $to)
        <option>{{$to}}</option>
    @endforeach
</x-p-select>
