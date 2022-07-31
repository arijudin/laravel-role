<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Truncate command instance.
     *
     * @return void
     */
    public function __construct()
    {
        User::truncate();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ACCESS_ROLE_ADMIN
        ]);
        $admin->assignRole(User::ACCESS_ROLE_ADMIN);

        $moderator = User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
            'password' => bcrypt('password'),
            'role' => User::ACCESS_ROLE_MODERATOR
        ]);
        $moderator->assignRole(User::ACCESS_ROLE_MODERATOR);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => User::ACCESS_ROLE_USER
        ]);
        $user->assignRole(User::ACCESS_ROLE_USER);

        // User::factory(100)->create();
        User::factory(100000)->create();
    }
}
