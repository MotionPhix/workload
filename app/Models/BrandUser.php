<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BrandUser extends Pivot
{
  protected $table = 'brand_user';

  public function role()
  {
    return $this->belongsTo(Role::class);
  }
}
