<?php

namespace xGrz\Dhl24UI;


use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use xGrz\Dhl24UI\Livewire\CreateShipment;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentCreate;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentDelete;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentEdit;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentsListing;
use xGrz\Dhl24UI\Livewire\Settings\CostsCenter\CostCenterCreate;
use xGrz\Dhl24UI\Livewire\Settings\CostsCenter\CostCenterDelete;
use xGrz\Dhl24UI\Livewire\Settings\CostsCenter\CostCenterEdit;
use xGrz\Dhl24UI\Livewire\Settings\CostsCenter\CostCenterListing;
use xGrz\Dhl24UI\Livewire\Settings\TrackingStates\TrackingStateEdit;
use xGrz\Dhl24UI\Livewire\Settings\TrackingStates\TrackingStateListing;
use xGrz\Dhl24UI\Livewire\ShipmentListItem;
use xGrz\Dhl24UI\Livewire\ShipmentServices;

class DHLUIServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        self::setupWebRouting();

        Livewire::component('create-shipment', CreateShipment::class);
        Livewire::component('shipment-item', ShipmentListItem::class);
        Livewire::component('shipment-services', ShipmentServices::class);
        Livewire::component('costs-center-listing', CostCenterListing::class);
        Livewire::component('cost-center-create', CostCenterCreate::class);
        Livewire::component('cost-center-edit', CostCenterEdit::class);
        Livewire::component('cost-center-delete', CostCenterDelete::class);
        Livewire::component('contents-listing', ContentsListing::class);
        Livewire::component('content-create', ContentCreate::class);
        Livewire::component('content-edit', ContentEdit::class);
        Livewire::component('content-delete', ContentDelete::class);
        Livewire::component('tracking-states-listing', TrackingStateListing::class);
        Livewire::component('tracking-event-edit', TrackingStateEdit::class);

    }

    private function setupWebRouting(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dhl-ui');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

}
