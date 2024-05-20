<?php

namespace xGrz\Dhl24UI\Http\Controllers;

class SettingsTrackingEventsController extends BaseController
{
    public function __invoke()
    {

        return view('dhl-ui::settings.tracking-settings-index', [
            'title' => 'Tracking events'
        ]);
    }



}
