<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
  protected $fillable = ['user_id', 'brand_id', 'action', 'details'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }
}
