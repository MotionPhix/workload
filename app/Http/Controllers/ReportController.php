<?php

namespace App\Http\Controllers;

use App\Exports\BrandAnalyticsExport;
use App\Exports\ProjectExport;
use App\Exports\TaskExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Task;
use App\Models\Project;
use App\Models\Brand;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
  // Generate a task report
  public function taskReport(Request $request, Task $task)
  {
    $pdf = Pdf::loadView('reports.task', compact('task'));
    return $pdf->download('task-report.pdf');
  }

  // Generate a project report
  public function projectReport(Request $request, Project $project)
  {
    $pdf = Pdf::loadView('reports.project', compact('project'));
    return $pdf->download('project-report.pdf');
  }

  // Generate a brand analytics report
  public function brandAnalyticsReport(Request $request, Brand $brand)
  {
    $pdf = Pdf::loadView('reports.brand-analytics', compact('brand'));
    return $pdf->download('brand-analytics-report.pdf');
  }

  // Export tasks to Excel
  public function exportTasks(Request $request)
  {
    return Excel::download(new TaskExport($request->all()), 'tasks.xlsx');
  }

  // Export projects to Excel
  public function exportProjects(Request $request)
  {
    return Excel::download(new ProjectExport($request->all()), 'projects.xlsx');
  }

  // Export brand analytics to Excel
  public function exportBrandAnalytics(Brand $brand)
  {
    return Excel::download(new BrandAnalyticsExport($brand), 'brand-analytics.xlsx');
  }
}
