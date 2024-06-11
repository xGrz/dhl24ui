<?php

namespace xGrz\Dhl24UI\Livewire;

use LivewireUI\Modal\ModalComponent;

class ShipmentCreateError extends ModalComponent
{
    public string $message = '';

    public function mount(string $message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return view('dhl-ui::shipments.livewire.shipment-create-error');
    }
}
