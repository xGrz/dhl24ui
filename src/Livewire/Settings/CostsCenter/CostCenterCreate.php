<?php

namespace xGrz\Dhl24UI\Livewire\Settings\CostsCenter;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Models\DHLCostCenter;

class CostCenterCreate extends ModalComponent
{
    #[Validate]
    public string $name;

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.costs-center.cost-center-create');
    }

    public function store(): void
    {
        $this->validate();
        DHLCostCenter::create(['name' => $this->name]);
        $this->closeModal();
        session()->flash('success', 'Cost Center has been created successfully.');
        $this->redirectRoute('dhl24.settings.costCenters.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_cost_centers', 'name')->whereNull('deleted_at')
            ],
        ];
    }

}
