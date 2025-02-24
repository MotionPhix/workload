<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:todo,in_progress,done',
      'priority' => 'required|in:low,medium,high',
      'due_date' => 'nullable|date',
      'project_id' => 'required|exists:projects,id',
    ]);

    Task::create($request->all());
    return redirect()->back()->with('success', 'Task created successfully!');
  }

  public function update(Request $request, Task $task)
  {
    $request->validate([
      'title' => 'sometimes|string|max:255',
      'description' => 'nullable|string',
      'status' => 'sometimes|in:todo,in_progress,done',
      'priority' => 'sometimes|in:low,medium,high',
      'due_date' => 'nullable|date',
    ]);

    $task->update($request->all());
    return redirect()->back()->with('success', 'Task updated successfully!');
  }

  public function destroy(Task $task)
  {
    $task->delete();
    return redirect()->back()->with('success', 'Task deleted successfully!');
  }
}
