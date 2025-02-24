<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');


Route::prefix('brands')->name('api.brands.')->group(function () {

  Route::get('/', function (Request $request) {
    $brands = \App\Models\Brand::ownedBy($request->user())->get();

    return response()->json($brands->map(function ($brand) {
      return [
        'id' => $brand->id,
        'name' => $brand->name,
        'logo' => $brand->media->map(function ($media) {
          return $media->getUrl();  // Get the URL for each media
        })[0],
      ];
    }));

  })->name('index');

})->middleware('auth:sanctum');
