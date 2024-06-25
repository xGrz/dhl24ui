<?php

namespace xGrz\Dhl24UI\Http\Controllers;


use xGrz\Dhl24\Models\DHLCostCenter;

class CostsCenterController extends BaseController
{

    public function show(DHLCostCenter $costCenter)
    {
        return view('dhl-ui::costs-center.show', [
            'title' => 'Cost center details',
            'costCenter' => $costCenter
        ]);
    }
}
