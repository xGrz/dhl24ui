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

    public function mount()
    {
        $this->shipments = DHLShipment::whereDoesntHave('courier_booking')
            ->whereDoesntHave('tracking')
            ->latest()
            ->limit(5)
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

    public function book()
    {
        $from = Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom);
        $to = Carbon::parse($this->pickupDate . ' ' . $this->pickupTo);
        $shipmentList = $this->shipments->filter( function ($shipment) {
            return in_array($shipment->number, $this->shipmentList);
        });
        try {
            $booked = DHL24::booking()->book($from, $to, $shipmentList, $this->info);
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
            ->filter(fn($shipment) => $shipment->number === (int) $this->shipmentList[0])
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
        $this->dateOptions = DHL24::booking()->options($this->postalCode)->availableDates();
        $this->pickupDate = $this->dateOptions[0];
    }

    private function refreshPickupFromOptions(): array
    {
        if (!$this->postalCode) return [];
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
        if (!$this->postalCode) return [];
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
