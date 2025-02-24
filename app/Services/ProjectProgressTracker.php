<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectProgress;

class ProjectProgressTracker
{
  public static function updateProgress(Project $project)
  {
    $completedTasks = $project->tasks()->where('status', 'done')->count();
    $totalTasks = $project->tasks()->count();
    $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

    ProjectProgress::updateOrCreate(
      ['project_id' => $project->id],
      [
        'completed_tasks' => $completedTasks,
        'total_tasks' => $totalTasks,
        'progress' => $progress
      ]
    );
  }
}
