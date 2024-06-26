<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLCostsCenterHistoryListing extends BaseComponent
{
    use WithPagination;

    #[Title('Cost centers history')]
    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-history-listing', [
            'costsCenters' => self::loadCostsCenter(),
        ])
            ->section('content')
            ->extends('p::app');
    }

    private function loadCostsCenter(): LengthAwarePaginator
    {
        return DHLCostCenter::onlyTrashed()
            ->orderBy('name', 'asc')
            ->withCount('shipments')
            ->withSum('shipments', 'cost')
            ->withAvg('shipments', 'cost')
            ->withCount('shipmentItems')
            ->withAvg('shipmentItems', 'quantity')
            ->paginate();
    }


    public function restore($costCenterId)
    {
        DHL24::costsCenter($costCenterId)->restore();
        session()->flash('success', 'Cost center restored.');
        $this->redirectRoute('dhl24.costs-center.index');
    }
}


