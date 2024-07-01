<?php

namespace xGrz\Dhl24UI\Livewire\Bookings;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Models\DHLCourierBooking;
use xGrz\Dhl24\Services\DHLBookingService;

class DHLCancelBookingModal extends ModalComponent
{

    public DHLCourierBooking $booking;

    public function render(): View
    {
        return view('dhl-ui::bookings.cancel-booking-modal');
    }

    public function cancelBooking(): void
    {
        try {
            (new DHLBookingService())->cancel($this->booking);
            session()->flash('success', 'Courier booking has been cancelled.');
            $this->redirectRoute('dhl24.bookings.index');
        } catch (DHL24Exception $e) {
            $this->dispatch('openModal', component: 'error-modal', arguments: [
                'title' => 'Shipment cannot be deleted',
                'message' => $e->getMessage(),
            ]);
        }

    }
}
