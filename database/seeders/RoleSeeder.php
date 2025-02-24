<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  public function run()
  {
    // Create roles
    $owner = Role::create(['name' => 'owner']);
    $admin = Role::create(['name' => 'admin']);
    $employee = Role::create(['name' => 'employee']);

    // Assign permissions to roles
    $permissions = Permission::all();

    // Owner has all permissions
    $owner->permissions()->attach($permissions);

    // Admin has most permissions except deleting brands
    $admin->permissions()->attach($permissions->where('name', '!=', 'delete_brand'));

    // Employee has limited permissions
    $employee->permissions()->attach($permissions->whereIn('name', ['create_task', 'edit_task']));
  }
}
