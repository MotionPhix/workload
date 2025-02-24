<?php

namespace App\Actions\Fortify;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
  use PasswordValidationRules;

  /**
   * Validate and create a newly registered user.
   *
   * @param array<string, string> $input
   */
  public function create(array $input): User
  {
    Validator::make($input, [
      'brand_name' => 'required|string|max:255',
      'brand_email' => 'required|email|max:255|unique:brands,email',
      'brand_website' => 'nullable|url|max:255',
      'brand_logo' => 'nullable|image|max:2048',
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => $this->passwordRules(),
      'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
    ], [
      'brand_name.required' => 'Please enter your brand name.',
      'brand_email.required' => 'Please enter your brand email.',
      'brand_email.unique' => 'This brand email is already registered.',
      'brand_website.url' => 'Please enter a valid website URL.',
      'brand_logo.image' => 'The logo must be an image file.',
      'brand_logo.max' => 'The logo must not be larger than 2MB.',
      'terms.accepted' => 'You must accept the terms and conditions.',
    ])->validate();

    $user = User::create([
      'name' => $input['name'],
      'email' => $input['email'],
      'password' => Hash::make($input['password']),
    ]);

    // Create the brand
    $brand = Brand::create([
      'user_id' => $user->id,
      'name' => $input['brand_name'],
      'email' => $input['brand_email'],
      'website' => $input['brand_website'] ?? null,
    ]);

    if (isset($input['brand_logo'])) {
      $brand->addMediaFromRequest('brand_logo')
        ->toMediaCollection('logo');
    }

    // Fetch the "owner" role
    $ownerRole = Role::where('name', 'owner')->first();

    if (!$ownerRole) {
      throw new \Exception('Owner role not found. Please seed the roles table.');
    }

    // $user->brands()->attach($brand->id, ['role' => 'owner']);

    // Attach the brand to the user with the "owner" role
    $user->brands()->attach($brand->id, ['role_id' => $ownerRole->id]);

    $user->update(['current_brand_id' => $brand->id]);

    return $user;
  }
}
