<?php

namespace xGrz\Dhl24UI\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class LabelController extends Controller
{
    public function __invoke(DHLShipment $shipment)
    {
        return DHL24::label($shipment)->download();
    }
}
