<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNeed;
use App\Models\Indicator;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{



    public function indicatorIndex()
    {

        $indicators = Indicator::all();

        return view('admin_setting.indicator.index', compact('indicators'));
    }

}
