<?php

namespace xGrz\Dhl24UI\Livewire\Settings\TrackingEvents;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLTrackingState;

class TrackingEventEdit extends ModalComponent
{
    public DHLTrackingState $event;
    public $types = [];

    #[Validate]
    public $description = '';
    public $system_description = '';
    public $type;

    public function mount(DHLTrackingState $event): void
    {
        $this->types = DHLStatusType::getOptions();
        $this->type = $event->type;
        $this->event = $event;
        $this->system_description = $event->system_description;
        $this->description = $event->description;
    }
    public function render(): View
    {
        return view('dhl-ui::settings.livewire.tracking-events.tracking-event-edit',[
            'title' => 'Edit event description',
        ]);
    }

    public function rules(): array
    {
        return [
            'description' => 'string',
            'type' => 'required'
        ];
    }

    public function update(): void
    {
        $this->validate();
        $this->event->update([
            'description' => $this->description,
            'type' => $this->type
        ]);
        $this->closeModal();
        session()->flash('success', 'Tracking event successfully updated.');
        $this->redirectRoute('dhl24.settings.tracking-events.index');
    }

}
