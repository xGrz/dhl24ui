<?php

namespace xGrz\Dhl24UI\Livewire\Bookings;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;
use xGrz\Dhl24UI\Livewire\BaseComponent;

class DHLBookingsCreate extends BaseComponent
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


    public function render(): View
    {
        self::loadShipments();
        self::findPostalCode();
        self::getDateOptions();
        $this->pickupFromOptions = self::refreshPickupFromOptions();
        $this->pickupToOptions = self::refreshPickupToOptions();

        return view('dhl-ui::bookings.bookings-create')
            ->extends('p::app')
            ->section('content');
    }

    public function book(): void
    {
        $from = Carbon::parse($this->pickupDate . ' ' . $this->pickupFrom);
        $to = Carbon::parse($this->pickupDate . ' ' . $this->pickupTo);
        try {
            DHL24::booking()->book($from, $to, self::getSelectedShipments(), $this->info);
            session()->flash('success', 'Courier successfully booked for ' . $from->format('d-m-Y') . ' between ' . $from->format('H:i') . '-' . $to->format('H:i'));
            $this->redirectRoute('dhl24.bookings.index');
        } catch (DHL24Exception $e) {
            $this->dispatch(
                'openModal',
                component: 'error-modal',
                arguments: [
                    'title' => 'Create booking failed',
                    'message' => $e->getMessage(),
                ]);
        }
    }

    public function loadShipments(): void
    {
        $shipmentDate = count($this->shipmentList)
            ? DHLShipment::select('shipment_date')->where('number', collect($this->shipmentList)->first())?->first()?->shipment_date
            : null;

        $this->shipments = DHLShipment::whereDoesntHave('courier_booking')
            ->with(['items', 'tracking'])
            ->when($shipmentDate, fn($query) => $query->whereDate('shipment_date', $shipmentDate))
            ->latest()
            ->limit(50)
            ->get();
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

    private function getDateOptions(): void
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
