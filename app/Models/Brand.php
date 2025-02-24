<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model implements HasMedia
{
  use SoftDeletes, InteractsWithMedia, HasSlug;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'website',
    'email',
    'phone',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'user_id',
  ];

  protected $appends = [
    'logo'
  ];

  public function getSlugOptions(): SlugOptions
  {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('logo')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml'])
      ->registerMediaConversions(function () {
        $this->addMediaConversion('thumb')
          ->width(100)
          ->height(100);

        $this->addMediaConversion('medium')
          ->width(300)
          ->height(300);
      });
  }

  public function users()
  {
    return $this->belongsToMany(User::class)->using(BrandUser::class)->withPivot('role_id');
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function departments()
  {
    return $this->hasMany(Department::class);
  }

  public function projects()
  {
    return $this->hasMany(Project::class);
  }

  public function invitations()
  {
    return $this->hasMany(Invitation::class);
  }

  public function logo(): Attribute {
    return Attribute::make(function () {
      $this->media->map(function ($m) {
        return $m->getUrl();  // Get the URL for each media
      })[0];
    });
  }

  public function isOwner(User $user)
  {
    return $this->user_id === $user->id;
  }

  public function hasUserWithRole(User $user, string $role)
  {
    return $this->users()->where('user_id', $user->id)->where('role', $role)->exists();
  }

  public function scopeOwnedBy($query, User $user)
  {
    return $query->where('user_id', $user->id);
  }

  public function scopeWithRole($query, User $user, string $role)
  {
    return $query->whereHas('users', function ($q) use ($user, $role) {
      $q->where('user_id', $user->id)->where('role', $role);
    });
  }

  public function delete()
  {
    // Delete media files
    $this->clearMediaCollection('logo');

    // Detach users
    $this->users()->detach();

    // Soft delete the brand
    parent::delete();
  }
}
