<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Company;
use App\Policies\CompanyPolicy;
use App\Models\Activity;
use App\Policies\CompanyActivityPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Company::class => CompanyPolicy::class,
        Activity::class => CompanyActivityPolicy::class,

    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
