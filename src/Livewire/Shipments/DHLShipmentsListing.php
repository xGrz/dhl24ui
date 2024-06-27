<?php

namespace xGrz\Dhl24UI\Livewire\Shipments;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLShipmentsListing extends BaseComponent
{
    use WithPagination;

    #[Title('Shipments')]
    public function render(): View
    {
        return view('dhl-ui::shipments.shipments-listing', [
            'shipments' => self::loadShipments()
        ])
            ->section('content')
            ->extends('p::app');
    }

    private function loadShipments(): LengthAwarePaginator
    {
        return DHL24::shipments()
            ->withDetails()
            ->orderByDesc('shipment_date')
            ->orderByDesc('number')
            ->paginate();
    }

}
