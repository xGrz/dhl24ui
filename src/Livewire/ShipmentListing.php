<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;

class ShipmentListing extends Component
{

    public function render(): View
    {
        return view('dhl-ui::shipments.livewire.shipment-listing', [
            'title' => 'Shipments',
            'shipments' => DHL24::shipments()
                ->withDetails()
                ->orderByDesc('shipment_date')
                ->orderByDesc('number')
                ->paginate()
        ]);
    }
}
