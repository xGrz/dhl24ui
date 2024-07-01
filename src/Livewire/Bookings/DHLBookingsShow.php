<?php

namespace xGrz\Dhl24UI\Livewire\Bookings;

use Illuminate\View\View;
use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLBookingsShow extends BaseComponent
{

    public DHLCourierBooking $booking;

    public function render(): View
    {
        return view('dhl-ui::bookings.bookings-show')
            ->extends('p::app')
            ->section('content');
    }
}
