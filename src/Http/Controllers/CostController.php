<?php

namespace xGrz\Dhl24UI\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\Dhl24\Jobs\GetShipmentCostJob;
use xGrz\Dhl24\Models\DHLShipment;

class CostController extends Controller
{
    public function __invoke(DHLShipment $shipment)
    {
        GetShipmentCostJob::dispatch($shipment);
        // DHL24::wizard($shipment)->getCost();
        return back();
    }
}
