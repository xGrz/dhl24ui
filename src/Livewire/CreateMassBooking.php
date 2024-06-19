<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class CreateMassBooking extends Component
{

    public Collection $shipments;
    public string|null $postalCode = null;
    public array $shipmentList = [];
    public bool $selectedAll = false;
    public array $dateOptions = [];
    public array $pickupFromOptions = [];
    public array $pickupToOptions = [];
    public string $pickupDate = '';
    public string $pickupFrom = '';
    public string $pickupTo = '';
    public string $info = '';

    public function mount(): void
    {
        $this->shipments = DHLShipment::whereDoesntHave('courier_booking')
            ->with('items')
            ->whereDoesntHave('tracking')
            ->latest()
            ->limit(10)
            ->get();
    }


    public function render()
    {
        self::findPostalCode();
        self::getDateOptions();
        $this->pickupFromOptions = self::refreshPickupFromOptions();
        $this->pickupToOptions = self::refreshPickupToOptions();

        return view('dhl-ui::bookings.create-mass-booking');
    }

    public function book(): void
    {
        $from = Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom);
        $to = Carbon::parse($this->pickupDate . ' ' . $this->pickupTo);
        try {
            DHL24::booking()->book($from, $to, self::getSelectedShipments(), $this->info);
        } catch (DHL24Exception $e) {
            dd($e->getMessage());
        }
        session()->flash('success', 'Courier successfully booked for ' . $from->format('d-m-Y') . ' between ' . $from->format('H:i') . '-' . $to->format('H:i'));
        $this->redirectRoute('dhl24.bookings.index');
    }

    public function updatedShipmentList(): void
    {
        $this->selectedAll = (count($this->shipmentList) === $this->shipments->count());
    }

    public function updatedSelectedAll(): void
    {
        if (!$this->selectedAll) {
            $this->shipmentList = [];
            return;
        }
        $this->shipmentList = $this->shipments->map(fn($shipment) => $shipment->number)->toArray();
    }

    public function updatedPickupDate(): void
    {
        $this->pickupFrom = '';
        $this->pickupTo = '';
    }

    private function findPostalCode(): void
    {
        if (!count($this->shipmentList)) {
            self::resetBooking();
            return;
        }

        $this->postalCode = $this->shipments
            ->filter(fn($shipment) => $shipment->number === (int)$this->shipmentList[0])
            ?->first()
            ->shipper_postal_code;
    }

    private function resetBooking(): void
    {
        $this->postalCode = null;
        $this->pickupFromOptions = [];
        $this->pickupToOptions = [];
        $this->pickupFrom = '';
        $this->pickupTo = '';
    }

    private function getDateOptions()
    {
        if (!$this->postalCode) {
            self::resetBooking();
            return;
        }
        $this->dateOptions = DHL24::booking()->options(self::getSelectedShipments())->availableDates();
        $this->pickupDate = $this->dateOptions[0];
    }

    private function getSelectedShipments()
    {
        return $this->shipments->filter(function ($shipment) {
            return in_array($shipment->number, $this->shipmentList);
        });

    }

    private function refreshPickupFromOptions(): array
    {
        if (!$this->postalCode) return [];
        $pickupFromOptions = DHL24::booking()
            ->options(self::getSelectedShipments())
            ->pickupStartingOptions(Carbon::parse($this->pickupDate));

        if (empty($this->pickupFrom)) $this->pickupFrom = collect($pickupFromOptions)->first();
        if (!in_array($this->pickupFrom, $pickupFromOptions)) {
            $this->pickupFrom = collect($pickupFromOptions)->first();
        }
        return $pickupFromOptions;
    }

    private function refreshPickupToOptions(): array
    {
        if (!$this->postalCode) return [];
        $pickupToOptions = DHL24::booking()
            ->options(self::getSelectedShipments())
            ->pickupEndingOptions(Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom));

        if (empty($this->pickupTo)) $this->pickupTo = end($pickupToOptions);
        if (!in_array($this->pickupTo, $pickupToOptions)) {
            $this->pickupTo = end($pickupToOptions);
        }
        return $pickupToOptions;
    }

}
