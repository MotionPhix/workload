<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
  public function run()
  {
    $permissions = [
      ['name' => 'create_task', 'description' => 'Create a new task'],
      ['name' => 'edit_task', 'description' => 'Edit an existing task'],
      ['name' => 'delete_task', 'description' => 'Delete a task'],
      ['name' => 'create_project', 'description' => 'Create a new project'],
      ['name' => 'edit_project', 'description' => 'Edit an existing project'],
      ['name' => 'delete_project', 'description' => 'Delete a project'],
      ['name' => 'invite_member', 'description' => 'Invite a new member to the brand'],
      ['name' => 'remove_member', 'description' => 'Remove a member from the brand'],
    ];

    foreach ($permissions as $permission) {
      Permission::create($permission);
    }
  }
}
