<?php

use App\Http\Controllers\CustomReportController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  Route::get('/', function () {
    return Inertia::render('Welcome');
  });

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

    Route::get(
      '/member-activity/{brand}/analytics',
      [\App\Http\Controllers\AnalyticsController::class, 'memberActivity']
    )->name('analytics.member-activity');

    Route::get(
      '/project-progress/{brand}/analytics',
      [\App\Http\Controllers\AnalyticsController::class, 'projectProgress']
    )->name('analytics.project-progress');

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

  Route::prefix('tasks')->name('tasks.')->group(function () {

    Route::post(
      '/comments/{task}',
      [\App\Http\Controllers\CommentController::class, 'store']
    )->middleware('permission:create_task')
      ->name('comments.store');

    Route::delete(
      '/comments/{comment}',
      [\App\Http\Controllers\CommentController::class, 'destroy']
    )->name('comments.destroy');

    Route::post(
      '/attachments/{task}',
      [\App\Http\Controllers\AttachmentController::class, 'store']
    )->name('attachments.store');

    Route::delete(
      '/attachments/{task}/{mediaId}',
      [\App\Http\Controllers\AttachmentController::class, 'destroy']
    )->middleware('permission:delete_task')
      ->name('attachments.destroy');
  });

  Route::get('/reports/tasks/export', [ReportController::class, 'exportTasks'])->name('reports.export-tasks');
  Route::get('/reports/projects/export', [ReportController::class, 'exportProjects'])->name('reports.export-projects');
  Route::get('/brands/{brand}/analytics/export', [ReportController::class, 'exportBrandAnalytics'])->name('reports.export-brand-analytics');

  Route::get('/tasks/{task}/report', [ReportController::class, 'taskReport'])->name('reports.task');
  Route::get('/projects/{project}/report', [ReportController::class, 'projectReport'])->name('reports.project');
  Route::get('/brands/{brand}/analytics-report', [ReportController::class, 'brandAnalyticsReport'])->name('reports.brand-analytics');

  Route::get('/reports/tasks', [CustomReportController::class, 'taskReport'])->name('reports.custom-task');
  Route::get('/reports/projects', [CustomReportController::class, 'projectReport'])->name('reports.custom-project');
});
