<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;


use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLCostsCenterShow extends BaseComponent
{
    use WithPagination;

    public DHLCostCenter $costCenter;
    public float $periodSum = 0;

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public function boot(): void
    {
        $this->from = $this->from ?: now()->startOfMonth()->format('Y-m-d');
        $this->to = $this->to ?: now()->format('Y-m-d');
    }

    #[Title('Costs center')]
    public function render(): View
    {
        self::countPeriodSum();
        self::applyDateRange();

        return view('dhl-ui::costs-center.costs-center-show')
            ->section('content')
            ->extends('p::app');
    }

    public function updatedFrom(): void
    {
        $this->setPage(1);
    }

    public function updatedTo(): void
    {
        $this->setPage(1);
        if (!$this->to) $this->to = now()->format('Y-m-d');
    }

    private function applyDateRange(): void
    {
        $this
            ->costCenter
            ->setRelation(
                'shipments',
                $this->costCenter
                    ->shipments()
                    ->with(['tracking'])
                    ->withCount('items')
                    ->when($this->from, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '>=', $this->from))
                    ->when($this->to, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '<=', $this->to))
                    ->orderBy('shipment_date', 'desc')
                    ->paginate()
            );
    }

    private function countPeriodSum(): void
    {
        $this->periodSum = $this
            ->costCenter
            ->shipments()
            ->when($this->from, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '>=', $this->from))
            ->when($this->to, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '<=', $this->to))
            ->sum('cost');
    }

}
