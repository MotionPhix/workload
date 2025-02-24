<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Brand;

class ActivityLogger
{
  public static function log(User $user, Brand $brand, string $action, array $details = [])
  {
    ActivityLog::create([
      'user_id' => $user->id,
      'brand_id' => $brand->id,
      'action' => $action,
      'details' => json_encode($details),
    ]);
  }
}
