<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::prefix('brands')->name('brands.')->group(function () {

    Route::get(
      '/',
      [\App\Http\Controllers\BrandController::class, 'index']
    )->name('index');

    Route::put(
      '/switch/{brand}',
      [\App\Http\Controllers\BrandController::class, 'switch']
    )->name('switch');

    Route::post(
      '/invitations/{brand}',
      [\App\Http\Controllers\InvitationController::class, 'store']
    )->middleware('brand.role:admin')
      ->name('invitations.store');

    Route::get(
      '/invitations/{token}/accept',
      [\App\Http\Controllers\InvitationController::class, 'accept']
    )->name('invitations.accept');

    Route::get(
      '/invitations/{token}/reject',
      [\App\Http\Controllers\InvitationController::class, 'reject']
    )->name('invitations.reject');

  });

  Route::prefix('projects')->name('projects.')->group(function () {

    Route::get(
      '/',
      [\App\Http\Controllers\ProjectController::class, 'index']
    )->name('index');

    Route::get(
      '/new-project',
      [\App\Http\Controllers\ProjectController::class, 'create']
    )->name('create');


    Route::delete(
      '/destroy-many',
      [\App\Http\Controllers\ProjectController::class, 'destroyMany']
    )->name('destroy-many');

  });
});
