<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  protected $fillable = [
    'user_id', 'report_type', 'period_start',
    'period_end', 'status', 'file_path'
  ];

  protected $casts = [
    'period_start' => 'datetime',
    'period_end' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
