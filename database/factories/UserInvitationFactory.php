<?php

namespace Database\Factories;

use App\Models\UserInvitation;
use App\Models\Company;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserInvitationFactory extends Factory
{
    protected $model = UserInvitation::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'token' => Str::uuid(),
            'company_id' => Company::factory(),
            'role_id' => Role::COMPANY_OWNER->value,
            'registered_at' => null,
        ];
    }
}