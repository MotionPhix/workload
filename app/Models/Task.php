<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{
  use SoftDeletes, InteractsWithMedia;

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

  // Define media collections
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('attachments')
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf', 'text/plain'])
      ->registerMediaConversions(function () {
        $this->addMediaConversion('thumb')
          ->width(100)
          ->height(100);
      });
  }

  // Relationship with comments
  public function comments()
  {
    return $this->hasMany(Comment::class);
  }
}
