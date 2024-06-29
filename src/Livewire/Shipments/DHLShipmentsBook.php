<?php

namespace xGrz\Dhl24UI\Livewire\Shipments;

use Illuminate\Support\Carbon;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentsBook extends ModalComponent
{
    public DHLShipment $shipment;
    public array $dateOptions = [];
    public string $pickupDate;
    public string|null $pickupFrom = null;
    public array $pickupFromOptions = [];
    public string|null $pickupTo = null;
    public array $pickupToOptions = [];
    public string $comment = '';
    public string $info = '';


    public function mount(): void
    {
        $this->dateOptions = DHL24::booking()
            ->options($this->shipment)
            ->availableDates();

        $this->pickupDate = $this->dateOptions[0];
    }

    public function render(): View
    {
        $this->pickupFromOptions = self::refreshPickupFromOptions();
        $this->pickupToOptions = self::refreshPickupToOptions();
        return view('dhl-ui::shipments.shipments-book');
    }

    public function book()
    {
        $from = Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom);
        $to = Carbon::parse($this->pickupDate . ' ' . $this->pickupTo);
        try {
            DHL24::booking()->book($from, $to, $this->shipment, $this->comment);
            session()->flash('success', 'Courier successfully booked for ' . $from->format('d-m-Y') . ' between ' . $from->format('H:i') . '-' . $to->format('H:i'));
            $this->redirectRoute('dhl24.shipments.index');
        } catch (DHL24Exception $e) {
            $this->dispatch('openModal', component: 'error-modal', arguments: ['title' => 'Courier booking failed', 'message' => $e->getMessage()]);
        }
    }

    private function refreshPickupFromOptions(): array
    {
        $pickupFromOptions = DHL24::booking()
            ->options($this->shipment)
            ->pickupStartingOptions(Carbon::parse($this->pickupDate));

        if (empty($this->pickupFrom)) $this->pickupFrom = collect($pickupFromOptions)->first();
        if (!in_array($this->pickupFrom, $pickupFromOptions)) {
            $this->pickupFrom = collect($pickupFromOptions)->first();
        }
        return $pickupFromOptions;
    }

    private function refreshPickupToOptions(): array
    {
        $pickupToOptions = DHL24::booking()
            ->options($this->shipment)
            ->pickupEndingOptions(Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom));

        if (empty($this->pickupTo)) $this->pickupTo = end($pickupToOptions);
        if (!in_array($this->pickupTo, $pickupToOptions)) {
            $this->pickupTo = end($pickupToOptions);
        }
        return $pickupToOptions;
    }
}
