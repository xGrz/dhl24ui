<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostsCenterListing extends BaseComponent
{
    use WithPagination;

    #[Title('Cost centers')]
    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-listing', [
            'costsCenters' => self::loadCostsCenter(),
            'hasHistory' => self::hasHistory(),
        ])
            ->section('content')
            ->extends('p::app');
    }

    private function loadCostsCenter(): LengthAwarePaginator
    {
        return DHLCostCenter::query()
            ->orderBy('name', 'asc')
            ->withCount('shipments')
            ->withSum('shipments', 'cost')
            ->withAvg('shipments', 'cost')
            ->withCount('shipmentItems')
            ->withAvg('shipmentItems', 'quantity')
            ->paginate();
    }

    private function hasHistory(): bool
    {
        return (bool)DHLCostCenter::onlyTrashed()->count();
    }

    public function setAsDefault($costCenterId)
    {
        DHL24::costsCenter($costCenterId)->setDefault();
        $name = DHLCostCenter::find($costCenterId)->name;
        session()->flash('info', "Default cost center has been changed to $name.");
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

}


