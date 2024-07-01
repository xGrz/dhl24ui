<?php

namespace xGrz\Dhl24UI\Livewire\Bookings;

use Illuminate\View\View;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLBookingsCreate extends BaseComponent
{
    public function render(): View
    {
        return view('livewire.d-h-l-bookings-create');
    }
}
