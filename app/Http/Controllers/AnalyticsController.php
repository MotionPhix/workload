<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ActivityLog;
use App\Models\ProjectProgress;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
  public function memberActivity(Brand $brand)
  {
    $activityLogs = ActivityLog::where('brand_id', $brand->id)
      ->with('user')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return response()->json($activityLogs);
  }

  public function projectProgress(Brand $brand)
  {
    $projectProgress = ProjectProgress::whereIn('project_id', $brand->projects->pluck('id'))
      ->with('project')
      ->get();

    return response()->json($projectProgress);
  }
}
