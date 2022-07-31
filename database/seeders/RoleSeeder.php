<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Truncate command instance.
     *
     * @return void
     */
    public function __construct()
    {
        Role::truncate();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = User::getAccessRoles();

        foreach ($roles as $role) {
          Role::create([
            'name' => $role,
            'guard_name' => 'web'
          ]);
        }

        //
    }
}
