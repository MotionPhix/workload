<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }

  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  public function brand(): BelongsTo
  {
    return $this->belongsTo(Brand::class);
  }
}
