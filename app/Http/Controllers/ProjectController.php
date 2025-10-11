<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sector;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        
        return view('project.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sectors = Sector::all();

       return view('project.create',compact('sectors'));
    }

    
    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:projects,title', // Ensure title is unique
            'description' => 'nullable|string',
            // Check that the sector_id exists in the 'sectors' table
            'sector_id' => 'required|integer|exists:sectors,id', 
        ]);

        // 2. Create the new Project record
        $project = Project::create($validatedData);

        // 3. Redirect to a relevant page with a success message
        // You might change 'project.index' to 'project.show' if you want to view the new project
        return redirect()->route('project.index') 
                         ->with('success', 'المشروع: "' . $project->title . '" تم اضافه!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('project.show', compact('project'));
    }

    public function stepsShow(Project $project)
    {
        return view('project.steps.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
