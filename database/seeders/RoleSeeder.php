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
      
         \App\Models\Role::insert([
        ['id' => 1, 'name' => 'Administrator'],
        ['id' => 2, 'name' => 'Company Owner'],
        ['id' => 3, 'name' => 'Customer'],
        ['id' => 4, 'name' => 'Guide'],
    ]);
    }
}
