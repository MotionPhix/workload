<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name', 'code', 'description', 'department_id',
    'start_date', 'end_date', 'status'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
  ];

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function tasks()
  {
    return $this->hasMany(Task::class);
  }

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }
}
