<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBrandRole
{
  public function handle(Request $request, Closure $next, string $role)
  {
    $brand = $request->route('brand');
    $user = $request->user();

    if (!$user->brands->contains($brand) || $user->brands->find($brand->id)->pivot->role->name !== $role) {
      abort(403, 'Unauthorized');
    }

    return $next($request);
  }
}
