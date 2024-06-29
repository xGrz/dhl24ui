@php use Illuminate\Support\Carbon; @endphp
<div>
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
