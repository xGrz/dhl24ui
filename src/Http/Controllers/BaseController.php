<?php

namespace xGrz\Dhl24UI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        View::share('qbp_appName', 'xGrz/DHL24');
        View::share('qbp_useTailwind', true);
        View::share('qbp_useAlpine', false);
        View::share('qbp_navigationTemplate', 'p::navigation.container');
        View::share('qbp_navigationItems', 'dhl-ui::layout.navigation-items');
        View::share('qbp_footerTemplate', 'p::footer.content');
    }

}
