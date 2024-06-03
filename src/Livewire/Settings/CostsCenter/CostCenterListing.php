<?php

namespace xGrz\Dhl24UI\Livewire\Settings\CostsCenter;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterListing extends Component
{
    public $costCenters = null;

    public function mount(): void
    {
        $this->costCenters = DHL24::costsCenter(false);
    }

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.costs-center.costs-center-listing');
    }

    public function delete($itemId): void
    {
        DHLCostCenter::destroy($itemId);
        $name = $this->costCenters->find($itemId)->name;
        session()->flash('success', "Cost center $name has been deleted.");
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

    public function setAsDefault($itemId): void
    {
        $this->costCenters->find($itemId)->update(['is_default' => true]);
        $name = $this->costCenters->find($itemId)->name;
        session()->flash('info', "Default cost center has been changed to $name.");
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }


}
