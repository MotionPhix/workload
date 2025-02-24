<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
  public function index(Request $request)
  {
    $projects = Project::with('user', 'tasks')
      ->where('brand_id', $request->user()->current_brand_id)
      ->get();

    return Inertia::render('Projects/Index', [
      'projects' => $projects,
    ]);
  }

  // Show the form for creating a new project
  public function create()
  {
    return Inertia::render('Projects/Create');
  }

  public function show(Project $project)
  {
    $project->load(['tasks' => function ($query) {
      $query->orderBy('due_date', 'asc');
    }]);

    return Inertia::render('Projects/Show', [
      'project' => $project,
      'tasks' => $project->tasks,
    ]);
  }

  // Show the form for editing the specified project
  public function edit(Project $project)
  {
    return Inertia::render('Projects/Edit', [
      'project' => $project,
    ]);
  }

  public function destroyMany(Request $request)
  {
    $request->validate(['ids' => 'required|array']);
    Project::whereIn('id', $request->ids)->delete();
    return redirect()->route('projects.index')->with('success', 'Selected projects deleted successfully!');
  }
}
