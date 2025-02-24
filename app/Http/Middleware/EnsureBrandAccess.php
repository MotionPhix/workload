<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBrandAccess
{
  public function handle(Request $request, Closure $next): Response
  {
    $user = $request->user();

    // Check if the requested resource belongs to the user's brand
    if ($request->route('brand') && $request->route('brand')->id !== $user->brand_id) {
      abort(403, 'You do not have access to this brand.');
    }

    return $next($request);
  }
}
