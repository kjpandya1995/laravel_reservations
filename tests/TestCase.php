<?php

namespace Tests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\RoleSeeder;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected bool $seed = true;
    protected string $seeder = RoleSeeder::class; 
}
