<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCompanyOwner() && $user->company_id === $company->id;
    }

    public function create(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCompanyOwner() && $user->company_id === $company->id;
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCompanyOwner() && $user->company_id === $company->id;
    }

    public function delete(User $user, Company $company): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCompanyOwner() && $user->company_id === $company->id;
    }
}
