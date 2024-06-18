<?php

namespace xGrz\Dhl24UI\Http\Controllers;


use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class SingleShipmentBookingController extends BaseController
{

    public function create(DHLShipment $shipment)
    {

        return view('dhl-ui::shipments.create-booking', [
            'title' => 'Book courier',
            'shipment' => $shipment,
            'dateOptions' => DHL24::booking()->options($shipment)->availableDates(),
        ]);
    }
}
