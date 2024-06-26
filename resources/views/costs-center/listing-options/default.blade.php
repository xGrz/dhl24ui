@if($center->is_default)
    <button type="button" class="text-yellow-500" disabled>
        <x-p::icons.star-full class="w-5 h-5"/>
    </button>
@else
    <button href="#" wire:click.prevent="setAsDefault({{$center->id}})"
            class="text-slate-500 hover:text-yellow-500 transition-all">
        <x-p::icons.star class="w-5 h-5"/>
    </button>
@endif
