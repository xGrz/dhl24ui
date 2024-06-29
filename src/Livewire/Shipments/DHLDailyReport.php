<?php

namespace xGrz\Dhl24UI\Livewire\Shipments;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Enums\DHLReportType;
use xGrz\Dhl24\Models\DHLShipment;

class DHLDailyReport extends ModalComponent
{
    public string $date = '';
    public string $type;
    public array $types = [];
    public array $dates = [];

    public function mount(): void
    {
        $this->types = DHLReportType::getOptions();
        $this->type = array_key_first($this->types);
        $this->dates = DHLShipment::select('shipment_date')
            ->limit(7)
            ->orderBy('shipment_date', 'desc')
            ->distinct()
            ->get()
            ->map(fn($shipment) => $shipment->shipment_date->format('d-m-Y'))
            ->toArray();
        $this->date = $this->dates[0];
    }

    public function render(): View
    {
        return view('dhl-ui::shipments.daily-report');
    }

    public function download(): void
    {
        $this->redirectRoute('dhl24.report', [
            'date' => $this->date,
            'type' => $this->type
        ]);
    }
}
