<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Task;
use App\Models\Project;
use App\Models\Brand;
use Illuminate\Http\Request;

class CustomReportController extends Controller
{
  // Generate a custom task report
  public function taskReport(Request $request)
  {
    $tasks = Task::query()
      ->when($request->status, fn($query) => $query->where('status', $request->status))
      ->when($request->priority, fn($query) => $query->where('priority', $request->priority))
      ->when($request->due_date, fn($query) => $query->where('due_date', $request->due_date))
      ->get();

    $pdf = Pdf::loadView('reports.custom-task', compact('tasks'));
    return $pdf->download('custom-task-report.pdf');
  }

  // Generate a custom project report
  public function projectReport(Request $request)
  {
    $projects = Project::query()
      ->when($request->status, fn($query) => $query->where('status', $request->status))
      ->when($request->start_date, fn($query) => $query->where('start_date', '>=', $request->start_date))
      ->when($request->end_date, fn($query) => $query->where('end_date', '<=', $request->end_date))
      ->get();

    $pdf = Pdf::loadView('reports.custom-project', compact('projects'));
    return $pdf->download('custom-project-report.pdf');
  }
}
