<?php

namespace xGrz\Dhl24UI\Livewire\Bookings;


use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLBookingsListing extends BaseComponent
{
    public function render()
    {
        return view('dhl-ui::bookings.bookings-listing', [
            'bookings' => DHLCourierBooking::orderByDesc('pickup_from')->with('shipments')->latest()->paginate(),
        ])
            ->extends('p::app')
            ->section('content');
    }
}
