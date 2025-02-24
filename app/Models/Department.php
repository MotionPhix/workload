<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use SoftDeletes;

  protected $fillable = ['name', 'code', 'description'];

  public function projects()
  {
    return $this->hasMany(Project::class);
  }

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }
}
