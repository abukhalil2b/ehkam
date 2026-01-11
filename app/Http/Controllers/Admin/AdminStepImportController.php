<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

// ✅ 1. ADD THESE IMPORTS
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StepsImport;

class AdminStepImportController extends Controller
{
    public function create(Project $project)
    {
        return view('admin.steps.import', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
    
            Excel::import(
                new StepsImport($project->id, $project->id), 
                $request->file('file')
            );
            
            return redirect()->back()->with('success', 'Steps imported successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
}