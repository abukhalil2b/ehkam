<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{

    public function index() {
        return view('timeline.index');
    }

    public function show() {
        return view('timeline.show');
    }
}
