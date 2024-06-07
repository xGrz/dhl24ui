<?php


use xGrz\Dhl24UI\Http\Controllers\CostController;
use xGrz\Dhl24UI\Http\Controllers\CourierBookingsController;
use xGrz\Dhl24UI\Http\Controllers\LabelController;
use xGrz\Dhl24UI\Http\Controllers\SettingsContentsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsCostCentersController;
use xGrz\Dhl24UI\Http\Controllers\SettingsTrackingEventsController;
use xGrz\Dhl24UI\Http\Controllers\ShipmentsController;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24.')
    ->group(function () {
        Route::get('/', fn() => to_route('dhl24.shipments.index')); // redirect only

        Route::get('/shipments/{shipment}/label', LabelController::class)->name('shipments.label');
        Route::get('/shipments/{shipment}/cost', CostController::class)->name('shipments.cost');
        Route::resource('/shipments', ShipmentsController::class);
        Route::resource('/bookings', CourierBookingsController::class);
        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', SettingsController::class)->name('index');
                Route::get('/costCenters', SettingsCostCentersController::class)->name('costCenters.index');
                Route::get('/contents', SettingsContentsController::class)->name('contents.index');
                Route::get('/tracking-states',SettingsTrackingEventsController::class)->name('tracking-states.index');
            });
    });

