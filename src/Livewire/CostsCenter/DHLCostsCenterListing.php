<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLCostsCenterListing extends BaseComponent
{
    use WithPagination;

    #[Title('Cost centers')]
    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-listing', [
            'costsCenters' => self::loadCostsCenter(),
        ])
            ->section('content')
            ->extends('p::app');
    }

    private function loadCostsCenter(): LengthAwarePaginator
    {
        return DHLCostCenter::query()
            ->withTrashed()
            ->orderByRaw('CASE WHEN `deleted_at` IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('name', 'asc')
            ->withCount('shipments')
            ->withSum('shipments', 'cost')
            ->withAvg('shipments', 'cost')
            ->withCount('shipmentItems')
            ->withAvg('shipmentItems', 'quantity')
            ->paginate();
    }

    public function setAsDefault($costCenterId): void
    {
        DHL24::costsCenter($costCenterId)->setDefault();
        $name = DHLCostCenter::find($costCenterId)->name;
        session()->flash('info', "Default cost center has been changed to $name.");
        $this->redirectRoute('dhl24.costs-center.index');
    }



}


