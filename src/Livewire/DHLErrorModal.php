<?php

namespace xGrz\Dhl24UI\Livewire;

use LivewireUI\Modal\ModalComponent;

class DHLErrorModal extends ModalComponent
{
    public string $message = '';
    public string $title = '';

    public function mount(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function render()
    {
        return view('dhl-ui::error.error-modal');
    }

    public function close(): void
    {
        $this
            ->forceClose()
            ->closeModal();
    }
}
