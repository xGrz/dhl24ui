<x-p-paper>
    <x-slot:title>Daily report</x-slot:title>

    <div class="grid gap-2">
        <x-p-select wire:model.live="date" label="Report date">
            @foreach($dates as $date)
                <option value="{{$date}}">{{$date}}</option>
            @endforeach
        </x-p-select>

        <x-p-select wire:model.live="type" label="Report type">
            @foreach($types as $key => $label)
                <option value="{{$key}}">{{$key}}</option>
            @endforeach
        </x-p-select>

        <div class="text-right">
            <x-p-button color="info" wire:click="download" :max="now()">
                Download
            </x-p-button>
        </div>
    </div>
</x-p-paper>
