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

         \App\Models\Role::insert([
        ['id' => 1, 'name' => 'Administrator'],
        ['id' => 2, 'name' => 'Company Owner'],
        ['id' => 3, 'name' => 'Customer'],
        ['id' => 4, 'name' => 'Guide'],
    ]);
    }
}


$user = App\Models\User::create([
    'name' => 'Krishna',
    'email' => 'kdthaker95@gmail.com',
    'password' => Hash::make('Laravel@12'),
    'role_id' => 2, 
    'company_id' => $company->id 
]);
