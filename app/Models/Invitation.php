<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
  protected $fillable = [
    'brand_id',
    'sender_id',
    'email',
    'role',
    'token',
    'accepted_at',
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  public function sender()
  {
    return $this->belongsTo(User::class, 'sender_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'email', 'email');
  }
}
