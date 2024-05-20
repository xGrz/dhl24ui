<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;

class ShipmentServices extends Component
{

    private string $postalCode = '';
    public array $services = [];
    public array $costsCenter = [];
    public array $contentSuggestions = [];
    public string $deliveryService;
    public string $costCenterName = '';
    public string $content = '';
    public string $comment = '';
    public bool $pdi = false;
    public bool $rod = false;
    public bool $owl = false;
    public string $value = '';
    public string $cod = '';

    public function mount(string|null $postalCode): void
    {
        self::postalCodeUpdated($postalCode);
        self::getCostCenters();
        self::getContentSuggestions();
    }

    #[On('postalCode-updated')]
    public function postalCodeUpdated($postalCode): void
    {
        $this->postalCode = $postalCode;
        if (strlen($this->postalCode) > 4) {
            self::checkServices();
        } else {
            $this->services = [];
        }
    }

    private function checkServices(): void
    {
        try {
            $options = DHL24::getDeliveryServices(str_replace('-', '', $this->postalCode));
            $this->services = json_decode(json_encode($options), true);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $this->services = [];
        }
    }

    public function render(): View
    {
        return view('dhl-ui::shipments.livewire.shipment-services');
    }

    private function getCostCenters(): void
    {
        $this->costsCenter = DHL24::getCostCenters();
        $this->costCenterName = $this->costsCenter[0] ?? '';
    }

    private function getContentSuggestions(): void
    {
        $this->contentSuggestions = DHL24::getContentSuggestions();
        $this->content = $this->contentSuggestions[0] ?? '';
    }


}
