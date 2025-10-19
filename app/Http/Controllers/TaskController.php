<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Activity;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        return view('task.index');
    }

    public function kanban()
    {
        return view('kanban');
    }

    public function kanban2()
    {
        return view('kanban2');
    }

    public function staff_index()
    {
        return view('staff_index');
    }

    public function question_result()
    {
        return view('question_result');
    }

    
}