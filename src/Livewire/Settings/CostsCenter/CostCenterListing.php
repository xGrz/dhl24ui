<?php

namespace xGrz\Dhl24UI\Livewire\Settings\CostsCenter;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;

class CostCenterListing extends Component
{
    public $costCenters = null;

    public function mount(): void
    {
        $this->costCenters = DHL24::costsCenter()->query()->get();
    }

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.costs-center.costs-center-listing');
    }

    public function delete($itemId): void
    {
        DHL24::costsCenter($itemId)->delete();
        $name = $this->costCenters->find($itemId)->name;
        session()->flash('success', "Cost center $name has been deleted.");
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

    public function setAsDefault($itemId): void
    {
        DHL24::costsCenter($itemId)->setDefault();
        $name = $this->costCenters->find($itemId)->name;
        session()->flash('info', "Default cost center has been changed to $name.");
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }


}
