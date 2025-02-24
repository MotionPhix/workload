<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'title', 'description', 'project_id', 'user_id',
    'status', 'priority', 'started_at', 'completed_at'
  ];

  protected $casts = [
    'started_at' => 'datetime',
    'completed_at' => 'datetime',
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
