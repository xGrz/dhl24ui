<?php

namespace xGrz\Dhl24UI\Livewire\Shipments;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLShipmentsShow extends BaseComponent
{

    public DHLShipment $shipment;

    #[Title('Shipment')]
    public function render(): View
    {
        $this->shipment->load(DHLShipment::getRelationsListForDetails());
        return view('dhl-ui::shipments.shipments-show')
            ->extends('p::app')
            ->section('content');
    }
}
