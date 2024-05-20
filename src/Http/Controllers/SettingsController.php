<?php

namespace xGrz\Dhl24UI\Http\Controllers;

class SettingsController extends BaseController
{
    public function __invoke()
    {
        return view('dhl-ui::settings.index', [
            'title' => 'Settings',
        ]);
    }


}
