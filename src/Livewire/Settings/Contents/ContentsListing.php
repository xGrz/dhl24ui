<?php

namespace xGrz\Dhl24UI\Livewire\Settings\Contents;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;

class ContentsListing extends Component
{

    public $contents;

    public function mount(): void
    {
        $this->contents = DHL24::contentSuggestions()->query()->sortedByNames()->get();
    }

    public function render(): View
    {
        return view('dhl-ui::settings.livewire.contents.contents-listing');
    }

    public function setAsDefault(int $itemId): void
    {
        DHL24::contentSuggestions($itemId)->setDefault();
        session()->flash('success', 'Default content has been set.');
        $this->redirectRoute('dhl24.settings.contents.index');
    }

    public function removeDefault(int $itemId): void
    {
        DHL24::contentSuggestions($itemId)->removeDefault();
        session()->flash('info', 'Default content has been removed.');
        $this->redirectRoute('dhl24.settings.contents.index');
    }
}
