<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
  public function handle(Request $request, Closure $next, string $permission)
  {
    $user = $request->user();
    $brand = $request->route('brand');

    if (!$user->hasPermission($brand, $permission)) {
      abort(403, 'Unauthorized');
    }

    return $next($request);
  }
}
