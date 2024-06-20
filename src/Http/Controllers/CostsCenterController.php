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
                ->withSum('shipments', 'cost')
                ->withAvg('shipments', 'cost')
                ->withCount('shipmentItems')
                ->withAvg('shipmentItems', 'quantity')
                ->paginate()
        ]);
    }

    public function show(DHLCostCenter $costCenter)
    {
        return view('dhl-ui::costs-center.show', [
            'title' => 'Cost center details',
            'costCenter' => $costCenter
        ]);
    }
}
