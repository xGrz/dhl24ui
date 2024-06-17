<?php

namespace xGrz\Dhl24UI\Http\Controllers;


use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24\Services\DHLBookingService;

class CourierBookingsController extends BaseController
{
    public function index()
    {
        return view('dhl-ui::bookings.index', [
            'title' => 'Courier Bookings',
            'bookings' => DHLCourierBooking::with('shipments')->latest()->paginate(),
        ]);
    }

    public function create()
    {
        return view('dhl-ui::bookings.create', [
            'title' => 'Book courier',
        ]);
    }

    public function bookCourier(DHLShipment $shipment)
    {
        (new DHLBookingService())->book('13:00', '15:00', $shipment);
        return back();
    }

    public function destroy(DHLCourierBooking $booking)
    {
        (new DHLBookingService())->cancel($booking);
        return back();
    }

}
