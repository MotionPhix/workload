<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ReportGenerationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateUserReports extends Command
{
  protected $signature = 'reports:generate';
  protected $description = 'Generate reports for users based on their settings';

  private $reportService;

  public function __construct(ReportGenerationService $reportService)
  {
    parent::__construct();
    $this->reportService = $reportService;
  }

  public function handle()
  {
    $users = User::with('settings')->get();

    foreach ($users as $user) {
      if (!$user->settings) {
        continue;
      }

      $currentTime = now()->format('H:i');
      $reportTime = Carbon::parse($user->settings->report_time)->format('H:i');

      if ($currentTime === $reportTime) {
        $this->reportService->generateReport($user, $user->settings->report_frequency);
      }
    }
  }
}

