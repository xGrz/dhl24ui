<?php

namespace xGrz\Dhl24UI\Http\Controllers;

class SettingsCostCentersController extends BaseController
{
    public function __invoke()
    {
        return view('dhl-ui::settings.costs-center-index', [
            'title' => 'Shipping costs center',
        ]);
    }


}
