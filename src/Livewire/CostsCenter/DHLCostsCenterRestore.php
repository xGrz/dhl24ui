<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostsCenterRestore extends ModalComponent
{
    public DHLCostCenter $costCenter;

    public function mount($costCenterId)
    {
        $this->costCenter = DHLCostCenter::onlyTrashed()->where('id', $costCenterId)->first();
    }

    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-restore');
    }

    public function restoreConfirmed(): void
    {
        $this->closeModal();
        DHL24::costsCenter($this->costCenter)->restore();
        session()->flash('success', 'Cost center has been restored successfully.');
        $this->redirectRoute('dhl24.costs-center.index');
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

}
