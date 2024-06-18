<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
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
}
