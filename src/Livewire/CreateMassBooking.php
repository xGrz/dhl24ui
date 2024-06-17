<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class CreateMassBooking extends Component
{

    public Collection $shipments;
    public array $shipmentList = [];
    public bool $selectedAll = false;

    public function mount()
    {
        $this->shipments = DHLShipment::whereDoesntHave('courier_booking')
            ->whereDoesntHave('tracking')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function book()
    {
        $bookings = DHL24::booking()->options($this->shipments->first()->shipper_postal_code, $this->shipments);
        dd( $bookings->getAvailableBookings(), $bookings->availableDates());
    }

    public function render()
    {
        return view('dhl-ui::bookings.create-mass-booking', [
            'bookingEnabled' => (bool)count($this->shipmentList)
        ]);
    }

    public function updatedShipmentList()
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
}
