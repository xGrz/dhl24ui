<?php

namespace xGrz\Dhl24UI\Http\Controllers;


use xGrz\Dhl24\Models\DHLCourierBooking;

class CourierBookingsController extends BaseController
{
    public function index()
    {
        return view('dhl-ui::bookings.index', [
            'title' => 'Courier Bookings',
            'bookings' => DHLCourierBooking::latest()->paginate()
        ]);
    }
}
