<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $query = Project::query();
        
        // Filter by project type if specified
        if ($request->has('type')) {
            $query->where('project_type', $request->type);
        }
        
        // Filter by status if specified
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $projects = $query->paginate(10);
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'target_end_date' => 'required|date|after:start_date',
            'project_type' => 'required|in:BDP,Calamity',
            'status' => 'required|in:Not Started,Ongoing,Delayed,Completed',
            'progress' => 'required|integer|min:0|max:100',
            'assigned_committee' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Project::create($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'target_end_date' => 'required|date|after:start_date',
            'project_type' => 'required|in:BDP,Calamity',
            'status' => 'required|in:Not Started,Ongoing,Delayed,Completed',
            'progress' => 'required|integer|min:0|max:100',
            'assigned_committee' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project->update($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
} 