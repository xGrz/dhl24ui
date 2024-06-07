<?php

namespace xGrz\Dhl24UI\Livewire\Settings\TrackingStates;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;

class TrackingStateListing extends Component
{
    public Collection $events;

    public function mount(): void
    {
        $this->events = DHL24::states()
            ->query()
            ->orderByTypes()
            ->get();
    }

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.tracking-states.tracking-state-listing');
    }


}
