<?php

namespace App\Services;

use App\Models\User;
use App\Models\Report;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;

class ReportGenerationService
{
  public function generateReport(User $user, $reportType = 'weekly')
  {
    $periodDates = $this->calculateReportPeriod($reportType);

    $tasks = Task::where('user_id', $user->id)
      ->whereBetween('created_at', [$periodDates['start'], $periodDates['end']])
      ->with(['project.department'])
      ->get();

    $report = Report::create([
      'user_id' => $user->id,
      'report_type' => $reportType,
      'period_start' => $periodDates['start'],
      'period_end' => $periodDates['end'],
      'status' => 'pending'
    ]);

    $pdf = PDF::loadView('reports.template', [
      'user' => $user,
      'tasks' => $tasks,
      'period' => $periodDates,
    ]);

    $filename = "report_{$user->id}_{$report->id}.pdf";
    Storage::put("reports/$filename", $pdf->output());

    $report->update([
      'status' => 'generated',
      'file_path' => "reports/$filename"
    ]);

    return $report;
  }

  private function calculateReportPeriod($reportType)
  {
    $now = Carbon::now();

    switch ($reportType) {
      case 'daily':
        return [
          'start' => $now->copy()->startOfDay(),
          'end' => $now->copy()->endOfDay(),
        ];
      case 'weekly':
        return [
          'start' => $now->copy()->startOfWeek(),
          'end' => $now->copy()->endOfWeek(),
        ];
      case 'fortnightly':
        return [
          'start' => $now->copy()->subDays(14)->startOfDay(),
          'end' => $now->copy()->endOfDay(),
        ];
      case 'monthly':
        return [
          'start' => $now->copy()->startOfMonth(),
          'end' => $now->copy()->endOfMonth(),
        ];
      default:
        throw new \InvalidArgumentException("Invalid report type: {$reportType}");
    }
  }
}
