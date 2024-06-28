<?php

namespace xGrz\Dhl24UI;


use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterCreate;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterDelete;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterEdit;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterListing;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterRestore;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterShow;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLShipmentDelete;
use xGrz\Dhl24UI\Livewire\CreateMassBooking;
use xGrz\Dhl24UI\Livewire\CreateShipment;
use xGrz\Dhl24UI\Livewire\CreateSingleShipmentBooking;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentCreate;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentDelete;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentEdit;
use xGrz\Dhl24UI\Livewire\Settings\Contents\ContentsListing;
use xGrz\Dhl24UI\Livewire\Settings\TrackingStates\TrackingStateEdit;
use xGrz\Dhl24UI\Livewire\Settings\TrackingStates\TrackingStateListing;
use xGrz\Dhl24UI\Livewire\ShipmentCreateError;
use xGrz\Dhl24UI\Livewire\ShipmentListing;
use xGrz\Dhl24UI\Livewire\ShipmentListItem;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsCreate;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsListing;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsShow;
use xGrz\Dhl24UI\Livewire\ShipmentServices;

class DHLUIServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        self::setupWebRouting();
        self::setupTranslations();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/dhl24ui'),
            ], 'dhl24ui');
        }

        Livewire::component('costs-center-listing', DHLCostsCenterListing::class);
        Livewire::component('costs-center-create', DHLCostsCenterCreate::class);
        Livewire::component('costs-center-show', DHLCostsCenterShow::class);
        Livewire::component('costs-center-edit', DHLCostsCenterEdit::class);
        Livewire::component('costs-center-delete', DHLCostsCenterDelete::class);
        Livewire::component('costs-center-restore', DHLCostsCenterRestore::class);

        Livewire::component('shipments-listing', DHLShipmentsListing::class);
        Livewire::component('shipments-create', DHLShipmentsCreate::class);
        Livewire::component('shipments-show', DHLShipmentsShow::class);
        Livewire::component('shipment-delete', DHLShipmentDelete::class);


        Livewire::component('create-shipment', CreateShipment::class);
        Livewire::component('error-modal', ShipmentCreateError::class);
        Livewire::component('shipment-listing', ShipmentListing::class);
        Livewire::component('shipment-item', ShipmentListItem::class);
        Livewire::component('shipment-services', ShipmentServices::class);

        Livewire::component('contents-listing', ContentsListing::class);
        Livewire::component('content-create', ContentCreate::class);
        Livewire::component('content-edit', ContentEdit::class);
        Livewire::component('content-delete', ContentDelete::class);
        Livewire::component('tracking-states-listing', TrackingStateListing::class);
        Livewire::component('tracking-event-edit', TrackingStateEdit::class);
        Livewire::component('create-single-shipment-booking', CreateSingleShipmentBooking::class);
        Livewire::component('create-mass-booking', CreateMassBooking::class);
        // Livewire::component('costs-center-details', CostsCenterDetails::class);
    }

    private function setupWebRouting(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dhl-ui');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function setupTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'dhl-ui');
    }

}
