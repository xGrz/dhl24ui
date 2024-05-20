<?php

namespace xGrz\Dhl24UI\Http\Controllers;

class SettingsContentsController extends BaseController
{
    public function __invoke()
    {
        return view('dhl-ui::settings.contents-index', [
            'title' => 'Shipping contents'
        ]);
    }



}
