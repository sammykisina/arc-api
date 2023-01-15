<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder {
    public function run(): void {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // create super-admin
        $superAdmin = Role::query()->where('slug', 'super-admin')->first();
        User::create([
            'work_id' => '34996980',
            'first_name' => 'Angeline',
            'last_name' => 'Mutua',
            'email' => 'angelinemutua@superadmin.arc',
            'active' => true,
            'password' => Hash::make(value: 'superadmin'),
            'created_by' => 'diana',
            'role_id' => $superAdmin->id,
        ]);

        // $superAdmin->roles()->attach(Role::where('slug', 'super-admin')->first());

        // create admin
        // $admin = Role::query()->where('slug', 'admin')->first();
        // User::create([
        //     'work_id' => '34996981',
        //     'first_name' => 'Jose',
        //     'last_name' => 'Mutua',
        //     'email' => 'jose@admin.arc',
        //     'active' => true,
        //     'password' => Hash::make(value: 'admin'),
        //     'created_by' => 'diana',
        //     'role_id' => $admin->id,
        // ]);

        // $bartender = Role::query()->where('slug', 'bartender')->first();
        // User::create([
        //     'work_id' => '34996982',
        //     'first_name' => 'sammy',
        //     'last_name' => 'kisina',
        //     'email' => 'sammy@bartender.arc',
        //     'active' => false,
        //     'password' => Hash::make(value: 'bartender'),
        //     'created_by' => 'diana',
        //     'role_id' => $bartender->id,
        // ]);

        // User::create([
        //     'work_id' => '34996983',
        //     'first_name' => 'Samuel',
        //     'last_name' => 'Mwaniki',
        //     'email' => 'samuel@bartender.arc',
        //     'active' => false,
        //     'password' => Hash::make(value: 'bartender'),
        //     'created_by' => 'diana',
        //     'role_id' => $bartender->id,
        // ]);

        // $waiter = Role::query()->where('slug', 'waiter')->first();
        // User::create([
        //     'work_id' => '34996990',
        //     'first_name' => 'Mercy',
        //     'last_name' => 'Mutua',
        //     'email' => 'mercy@waiter.arc',
        //     'active' => false,
        //     'password' => Hash::make(value: 'waiter'),
        //     'created_by' => 'diana',
        //     'role_id' => $waiter->id,
        // ]);

        // User::create([
        //     'work_id' => '34996991',
        //     'first_name' => 'Lucy',
        //     'last_name' => 'Mutua',
        //     'email' => 'lucy@waiter.arc',
        //     'active' => false,
        //     'password' => Hash::make(value: 'waiter'),
        //     'created_by' => 'diana',
        //     'role_id' => $waiter->id,
        // ]);
    }
}
