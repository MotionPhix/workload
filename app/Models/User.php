<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens;

  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory;
  use HasProfilePhoto;
  use Notifiable;
  use TwoFactorAuthenticatable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'current_brand_id',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'two_factor_recovery_codes',
    'two_factor_secret',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array<int, string>
   */
  protected $appends = [
    'profile_photo_url',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function brands()
  {
    return $this->belongsToMany(Brand::class)->using(BrandUser::class)->withPivot('role_id');
  }

  public function invitations()
  {
    return $this->hasMany(Invitation::class, 'email', 'email');
  }

  public function ownedBrands()
  {
    return $this->hasMany(Brand::class, 'user_id');
  }

  public function currentBrand()
  {
    return $this->belongsTo(Brand::class, 'current_brand_id');
  }

  public function hasPermission(Brand $brand, string $permission)
  {
    $role = $this->brands()->where('brand_id', $brand->id)->first()->pivot->role;
    return $role->permissions->contains('name', $permission);
  }
}
