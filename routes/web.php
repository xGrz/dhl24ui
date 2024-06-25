<?php


use xGrz\Dhl24UI\Http\Controllers\CostController;
use xGrz\Dhl24UI\Http\Controllers\CostsCenterController;
use xGrz\Dhl24UI\Http\Controllers\CourierBookingsController;
use xGrz\Dhl24UI\Http\Controllers\LabelController;
use xGrz\Dhl24UI\Http\Controllers\SettingsContentsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsCostCentersController;
use xGrz\Dhl24UI\Http\Controllers\SettingsTrackingStatesController;
use xGrz\Dhl24UI\Http\Controllers\ShipmentsController;
use xGrz\Dhl24UI\Http\Controllers\SingleShipmentBookingController;
use xGrz\Dhl24UI\Livewire\DHLCostsCenterListing;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24.')
    ->group(function () {
        Route::get('/', fn() => to_route('dhl24.shipments.index')); // redirect only

        Route::get('/shipments/{shipment}/label', LabelController::class)->name('shipments.label');
        Route::get('/shipments/{shipment}/cost', CostController::class)->name('shipments.cost');
        Route::name('shipments.booking.')
            ->prefix('shipments/{shipment}')
            ->group(function () {
                Route::get('create-booking', [SingleShipmentBookingController::class, 'create'])->name('create');

            });
        Route::resource('/shipments', ShipmentsController::class);
        Route::resource('/bookings', CourierBookingsController::class);
        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', SettingsController::class)->name('index');
                Route::get('/costCenters', SettingsCostCentersController::class)->name('costCenters.index');
                Route::get('/contents', SettingsContentsController::class)->name('contents.index');
                Route::get('/tracking-states',SettingsTrackingStatesController::class)->name('tracking-states.index');
            });
        Route::get('costs-center', DHLCostsCenterListing::class)->name('costs-center.index');
        Route::get('costs-center/{costCenter}', [CostsCenterController::class, 'show'])->name('costs-center.show')->withTrashed();
    });

