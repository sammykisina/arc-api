<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domains\Shared\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder {
    public function run(): void {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'Bartender', 'slug' => 'bartender'],
            ['name' => 'Waiter', 'slug' => 'waiter'],
            ['name' => 'User', 'slug' => 'user'],
        ];

        collect($roles)->each(function ($role) {
            Role::create($role);
        });
    }
}
