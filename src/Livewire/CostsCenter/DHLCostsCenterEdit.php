<?php

namespace xGrz\Dhl24UI\Livewire\CostsCenter;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostsCenterEdit extends ModalComponent
{
    public ?DHLCostCenter $costCenter = null;

    #[Validate]
    public string $name = '';


    public function mount(DHLCostCenter $costCenter = null): void
    {
        $this->name = $costCenter->name;
    }

    public function render(): View
    {
        return view('dhl-ui::costs-center.costs-center-edit', [
            'title' => 'Edit ' . $this->costCenter->name,
        ]);
    }

    public function update(): void
    {
        $this->validate();
        DHL24::costsCenter($this->costCenter)->rename($this->name);
        $this->closeModal();
        session()->flash('success', 'Cost center has been updated successfully.');
        $this->redirectRoute('dhl24.costs-center.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_cost_centers', 'name')->ignore($this->costCenter->id)->whereNull('deleted_at'),
            ],
        ];
    }


}
