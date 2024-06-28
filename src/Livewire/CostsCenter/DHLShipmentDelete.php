<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

class DHLShipmentDelete extends ModalComponent
{
    public DHLShipment $shipment;


    public function render(): View
    {
        return view('dhl-ui::shipments.shipment-delete');
    }

    public function confirmed()
    {
        try {
            DHL24::deleteShipment($this->shipment);
            session()->flash('success', 'Shipment deleted.');
            $this->redirectRoute('dhl24.shipments.index');
        } catch (DHL24Exception $e) {
            $this->dispatch('openModal', component: 'shipment-create-error', arguments: [
                'title' => 'Shipment cannot be deleted',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function cancel(): void
    {
        $this->closeModal();
    }


}
