<?php

namespace xGrz\Dhl24UI\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostsCenterDetails extends Component
{
    use WithPagination;

    public DHLCostCenter $costCenter;
    public float $periodSum = 0;
    public ?string $from = null;
    public ?string $to = null;

    public function mount(DHLCostCenter $costCenter): void
    {
        $this->costCenter = $costCenter;
        $this->to = now()->format('Y-m-d');
    }

    public function render()
    {
        self::countPeriodSum();
        self::applyDateRange();
        return view('dhl-ui::costs-center.livewire.costs-center-details');
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

    protected $queryString = [
        'from' => ['except' => ''],
        'to' => ['except' => ''],
    ];

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

    private function countPeriodSum()
    {
        $this->periodSum = $this
            ->costCenter
            ->shipments()
            ->when($this->from, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '>=', $this->from))
            ->when($this->to, fn($shipmentQuery) => $shipmentQuery->where('shipment_date', '<=', $this->to))
            ->sum('cost');
    }
}



