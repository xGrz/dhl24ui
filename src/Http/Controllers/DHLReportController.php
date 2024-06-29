<?php

namespace xGrz\Dhl24UI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use xGrz\Dhl24\Enums\DHLReportType;
use xGrz\Dhl24\Facades\DHL24;

class DHLReportController extends Controller
{
    public function __invoke(string $date, string $type)
    {
        try {
            $date = Carbon::parse($date);
            $type = DHLReportType::tryFrom($type);
        } catch (\Exception $e) {
            abort(404);
        }

        return DHL24::report($date, $type)->download();
    }
}
