<?php

namespace xGrz\Dhl24UI\Http\Controllers;


use xGrz\Dhl24\Models\DHLCostCenter;

class CostsCenterController extends BaseController
{
    public function index()
    {
        return view('dhl-ui::costs-center.index', [
            'title' => 'Costs centers',
            'costsCenters' => DHLCostCenter::withTrashed()
                ->orderByRaw('CASE WHEN `deleted_at` IS NOT NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('name', 'asc')
                ->withCount('shipments')
                ->paginate()
        ]);
    }

    public function show(DHLCostCenter $costCenter)
    {
        dd($costCenter);
    }
}

