<?php

namespace xGrz\Dhl24UI\Http\Controllers;

use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24UI\Http\Requests\StoreShipmentRequest;

class ShipmentsController extends BaseController
{
    public function index()
    {
        return view('dhl-ui::shipments.index', [
            'title' => 'Shipments',
            'shipments' => DHLShipment::withDetails()->latest()->paginate()
        ]);
    }

    public function create()
    {
        return view('dhl-ui::shipments.create', [
            'title' => 'Crete shipment',
        ]);
    }

    public function show(DHLShipment $shipment)
    {
        $shipment = DHL24::getShipment($shipment->id);
        return view('dhl-ui::shipments.show', [
            'title' => 'Shipment',
            'shipment' => $shipment
        ]);
    }

    public function store(StoreShipmentRequest $request)
    {
        dd($request->all());
    }

}
