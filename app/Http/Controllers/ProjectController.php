<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
  public function index(Request $request)
  {
    $query = Project::query()
      ->with('user', 'tasks')
      ->where('brand_id', $request->user()->current_brand_id);

    // Search
    if ($request->has('search')) {
      $search = $request->get('search');
      $query->where('name', 'like', "%{$search}%");
    }

    // Status filter
    if ($request->has('status') && $request->get('status') !== 'all') {
      $query->where('status', $request->get('status'));
    }

    // Sorting
    $sortField = $request->get('sort', 'name');
    $sortDirection = $request->get('order', 'asc');

    $allowedSortFields = ['name', 'status', 'priority', 'due_date', 'created_at'];

    if (in_array($sortField, $allowedSortFields)) {
      $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
    }

    // Pagination
    $projects = $query->paginate(10)
      ->withQueryString();

    return Inertia::render('Projects/Index', [
      'projects' => $projects,
      'filters' => [
        'search' => $request->get('search'),
        'status' => $request->get('status'),
        'sort' => $sortField,
        'order' => $sortDirection,
      ],
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

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:planned,in_progress,completed',
      'priority' => 'required|in:low,medium,high',
      'due_date' => 'required|date',
    ]);

    $project = Project::create([
      ...$validated,
      'brand_id' => $request->user()->current_brand_id,
      'user_id' => $request->user()->id,
    ]);

    return redirect()
      ->route('projects.show', $project)
      ->with('success', 'Project created successfully.');
  }

  public function update(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:planned,in_progress,completed',
      'priority' => 'required|in:low,medium,high',
      'due_date' => 'required|date',
    ]);

    $project->update($validated);

    return redirect()
      ->route('projects.show', $project)
      ->with('success', 'Project updated successfully.');
  }

  public function destroy(Project $project)
  {
    $project->delete();

    return redirect()
      ->route('projects.index')
      ->with('success', 'Project deleted successfully.');
  }

  public function destroyMany(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'exists:projects,id'
    ]);

    Project::whereIn('id', $validated['ids'])
      ->where('brand_id', $request->user()->current_brand_id)
      ->delete();

    return redirect()
      ->route('projects.index')
      ->with('success', 'Selected projects deleted successfully.');
  }
}
