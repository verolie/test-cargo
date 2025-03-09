<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table.
     */
    public function run(): void
    {
        $roles = ['admin', 'user'];

        foreach ($roles as $role) {
            Role::create(['role' => $role]);
        }
    }
}
