<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::create(['name' => 'administrator']);
        // Role::create(['name' => 'company owner']);
        // Role::create(['name' => 'customer']);
        // Role::create(['name' => 'guide']);

         Role::insert([
        ['id' => 1, 'name' => 'ADMINISTRATOR'],
        ['id' => 2, 'name' => 'COMPANY_OWNER'],
        ['id' => 3, 'name' => 'CUSTOMER'],
        ['id' => 4, 'name' => 'GUIDE'],
    ]);
    }
}
