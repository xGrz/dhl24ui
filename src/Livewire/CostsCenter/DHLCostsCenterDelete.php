<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostsCenterDelete extends ModalComponent
{
    public ?DHLCostCenter $costCenter = null;

    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-delete');
    }

    public function deleteConfirmed(): void
    {
        $this->closeModal();
        DHL24::costsCenter($this->costCenter)->delete();
        session()->flash('success', 'Cost center has been deleted successfully.');
        $this->redirectRoute('dhl24.costs-center.index');
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

}
