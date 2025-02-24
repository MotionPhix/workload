<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
  public function index()
  {
    $brands = Brand::query()
      ->select(['id', 'name', 'website', 'email'])
      ->withCount(['users', 'departments', 'projects'])
      ->get()
      ->map(function ($brand) {
        return [
          ...$brand->toArray(),
          'logo_url' => $brand->getFirstMediaUrl('logo', 'thumb'),
        ];
      });

    return Inertia::render('Brands/Index', [
      'brands' => $brands,
    ]);
  }

  public function create()
  {
    return Inertia::render('Brands/Form');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'website' => 'nullable|url|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string',
      'city' => 'nullable|string|max:255',
      'state' => 'nullable|string|max:255',
      'country' => 'nullable|string|max:255',
      'postal_code' => 'nullable|string|max:255',
      'logo' => 'nullable|image|max:2048',
    ]);

    $brand = Brand::create([
      'user_id' => auth()->id(),
      ...$request->only('name', 'description', 'website', 'email', 'phone', 'address', 'city', 'state', 'country', 'postal_code'),
    ]);

    // Assign the owner role
    $brand->users()->attach(auth()->id(), ['role' => 'owner']);

    if ($request->hasFile('logo')) {
      $brand->addMediaFromRequest('logo')
        ->toMediaCollection('logo');
    }

    return redirect()->route('brands.index')
      ->with('success', 'Brand created successfully.');
  }

  public function edit(Brand $brand)
  {
    return Inertia::render('Brands/Form', [
      'brand' => [
        ...$brand->toArray(),
        'logo_url' => $brand->getFirstMediaUrl('logo'),
      ],
    ]);
  }

  public function update(Request $request, Brand $brand)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'website' => 'nullable|url|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string',
      'city' => 'nullable|string|max:255',
      'state' => 'nullable|string|max:255',
      'country' => 'nullable|string|max:255',
      'postal_code' => 'nullable|string|max:255',
      'logo' => 'nullable|image|max:2048',
    ]);

    $brand->update($validated);

    if ($request->hasFile('logo')) {
      $brand->clearMediaCollection('logo');
      $brand->addMediaFromRequest('logo')
        ->toMediaCollection('logo');
    }

    return redirect()->route('brands.index')
      ->with('success', 'Brand updated successfully.');
  }

  public function destroy(Brand $brand)
  {
    $brand->delete();

    return redirect()->route('brands.index')
      ->with('success', 'Brand deleted successfully.');
  }

  public function updateLogo(Request $request, Brand $brand)
  {
    $request->validate([
      'logo' => 'required|image|mimes:jpeg,png,svg|max:2048',
    ]);

    $brand->addMedia($request->file('logo'))->toMediaCollection('logo');

    return redirect()->back()->with('success', 'Logo updated successfully!');
  }

  public function switch(Brand $brand)
  {
    auth()->user()->update(['current_brand_id' => $brand->id]);
    return redirect()->back()->with('success', 'Brand switched successfully!');
  }
}
