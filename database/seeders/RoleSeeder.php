<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\Role as RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
    //      \App\Models\Role::insert([
    //     ['id' => 1, 'name' => 'Administrator'],
    //     ['id' => 2, 'name' => 'Company Owner'],
    //     ['id' => 3, 'name' => 'Customer'],
    //     ['id' => 4, 'name' => 'Guide'],
    // ]);
    $roles = [
        ['id' => RoleEnum::ADMINISTRATOR->value, 'name' => 'Administrator'],
        ['id' => RoleEnum::COMPANY_OWNER->value, 'name' => 'Company Owner'],
        ['id' => RoleEnum::CUSTOMER->value, 'name' => 'Customer'],
        ['id' => RoleEnum::GUIDE->value, 'name' => 'Guide'],
    ];

    foreach ($roles as $role) {
        Role::updateOrCreate(['id' => $role['id']], $role);
    }
    }
}
