<?php

namespace xGrz\Dhl24UI\Livewire;


use Illuminate\Support\Carbon;
use Livewire\Component;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class CreateSingleShipmentBooking extends Component
{
    public DHLShipment $shipment;
    public string $postalCode = '';
    public array $dateOptions = [];
    public string $pickupDate;
    public string|null $pickupFrom = null;
    public array $pickupFromOptions = [];
    public string|null $pickupTo = null;
    public array $pickupToOptions = [];
    public string $comment = '';

    public function mount(DHLShipment $shipment, array $dateOptions): void
    {
        $this->shipment = $shipment;
        $this->postalCode = $shipment->shipper_postal_code;
        $this->dateOptions = $dateOptions;
        $this->pickupDate = $dateOptions[0];
    }

    public function render()
    {
        $this->pickupFromOptions = self::refreshPickupFromOptions();
        $this->pickupToOptions = self::refreshPickupToOptions();
        return view('dhl-ui::shipments.livewire.create-single-shipment-booking');
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
            $this->dispatch('openModal', component: 'shipment-create-error', arguments: ['title' => 'Courier booking failed', 'message' => $e->getMessage()]);
        }
    }

    private function refreshPickupFromOptions(): array
    {
        $pickupFromOptions = DHL24::booking()
            ->options($this->postalCode)
            ->pickupStartingOptions(Carbon::parse($this->pickupDate));
        $testArr = [];
        foreach ($pickupFromOptions as $pickupFromOption) {
            $testArr[Carbon::parse($this->pickupDate)->format('Ymd') . str_replace(':', '', $pickupFromOption)] = $pickupFromOption;
        }
        $pickupFromOptions = $testArr;

        if (empty($this->pickupFrom)) $this->pickupFrom = collect($pickupFromOptions)->first();
        if (!in_array($this->pickupFrom, $pickupFromOptions)) {
            $this->pickupFrom = collect($pickupFromOptions)->first();
        }
        return $pickupFromOptions;
    }

    private function refreshPickupToOptions(): array
    {
        $pickupToOptions = DHL24::booking()
            ->options($this->postalCode)
            ->pickupEndingOptions(Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom));
        $testArr = [];
        foreach ($pickupToOptions as $pickupToOption) {
            $testArr[Carbon::parse($this->pickupDate)->format('Ymd') . str_replace(':', '', $pickupToOption)] = $pickupToOption;
        }
        $pickupToOptions = $testArr;

        if (empty($this->pickupTo)) $this->pickupTo = end($pickupToOptions);
        if (!in_array($this->pickupTo, $pickupToOptions)) {
            $this->pickupTo = end($pickupToOptions);
        }
        return $pickupToOptions;
    }


}
