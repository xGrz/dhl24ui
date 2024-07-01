<?php


use xGrz\Dhl24UI\Http\Controllers\CostController;
use xGrz\Dhl24UI\Http\Controllers\DHLLabelController;
use xGrz\Dhl24UI\Http\Controllers\DHLReportController;
use xGrz\Dhl24UI\Http\Controllers\SettingsContentsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsController;
use xGrz\Dhl24UI\Http\Controllers\SettingsTrackingStatesController;
use xGrz\Dhl24UI\Http\Controllers\SingleShipmentBookingController;
use xGrz\Dhl24UI\Livewire\Bookings\DHLBookingsCreate;
use xGrz\Dhl24UI\Livewire\Bookings\DHLBookingsListing;
use xGrz\Dhl24UI\Livewire\Bookings\DHLBookingsShow;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterListing;
use xGrz\Dhl24UI\Livewire\CostsCenter\DHLCostsCenterShow;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsCreate;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsListing;
use xGrz\Dhl24UI\Livewire\Shipments\DHLShipmentsShow;

Route::middleware(['web'])
    ->prefix('dhl')
    ->name('dhl24.')
    ->group(function () {
        Route::get('/', fn() => to_route('dhl24.shipments.index')); // redirect only


        Route::get('/shipments/{shipment}/cost', CostController::class)->name('shipments.cost');
        Route::name('shipments.booking.')
            ->prefix('shipments/{shipment}')
            ->group(function () {
                Route::get('create-booking', [SingleShipmentBookingController::class, 'create'])->name('create');

            });
        Route::get('shipments', DHLShipmentsListing::class)->name('shipments.index');
        Route::get('shipments/create', DHLShipmentsCreate::class)->name('shipments.create');
        Route::get('/shipments/{shipment}/label', DHLLabelController::class)->name('shipments.label');
        Route::get('/shipments/{shipment}', DHLShipmentsShow::class)->name('shipments.show');

        Route::get('report/{date}/{type}', DHLReportController::class)->name('report')
            ->where('date', '[0-9]{2}-[0-9]{2}-[0-9]{4}');

        Route::get('bookings', DHLBookingsListing::class)->name('bookings.index');
        Route::get('bookings/create', DHLBookingsCreate::class)->name('bookings.create');
        Route::get('bookings/{booking}', DHLBookingsShow::class)->name('bookings.show');

        //Route::resource('/shipments', ShipmentsController::class);
        //Route::resource('/bookings', CourierBookingsController::class);
        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', SettingsController::class)->name('index');
                Route::get('/contents', SettingsContentsController::class)->name('contents.index');
                Route::get('/tracking-states', SettingsTrackingStatesController::class)->name('tracking-states.index');
            });

        Route::get('costs-center', DHLCostsCenterListing::class)->name('costs-center.index');
        Route::get('costs-center/{costCenter}', DHLCostsCenterShow::class)->name('costs-center.show')->withTrashed();
    });

