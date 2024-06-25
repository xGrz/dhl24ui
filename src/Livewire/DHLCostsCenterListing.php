<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostsCenterListing extends BaseComponent
{
    use WithPagination;


    #[Title('Costs center')]
    public function render()
    {
        return view('dhl-ui::costs-center.livewire.costs-center-listing', [
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
}


