<?php

namespace xGrz\Dhl24UI\Livewire\Settings\Contents;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Facades\DHL24;

class ContentCreate extends ModalComponent
{
    #[Validate]
    public string $name = '';

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.contents.content-create');
    }

    public function store(): void
    {
        $this->validate();
        $this->closeModal();
        DHL24::contentSuggestions()->add($this->name);
        session()->flash('success', 'Content suggestion has been added successfully.');
        $this->redirectRoute('dhl24.settings.contents.index');

    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('dhl_contents', 'name')
            ],
        ];
    }
}
