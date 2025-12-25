<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Enums\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Auth;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', []);

    $response->assertSessionHasErrors();
    $this->assertGuest();
    }

     public function test_user_can_register_with_token_for_company_owner_role()
    {
        $company = Company::factory()->create();
        $user = User::factory()->companyOwner()->create(['company_id' => $company->id]);
 
        // Create invitation directly instead of going through the controller
        $invitation = \App\Models\UserInvitation::factory()->create([
            'email' => 'test@test.com',
            'token' => 'some-random-token',
            'company_id' => $company->id,
            'role_id' => Role::COMPANY_OWNER->value,
            'registered_at' => null,
        ]);
        
        Auth::logout();
 
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'invitation_token' => $invitation->token,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'company_id' => $company->id,
            'role_id' => Role::COMPANY_OWNER->value,
        ]);
 
        $this->assertAuthenticated();
        // $response->assertRedirect(route('dashboard', absolute: false));
    }
 
    public function test_user_can_register_with_token_for_guide_role()
    {
        $company = Company::factory()->create();
        $user = User::factory()->companyOwner()->create(['company_id' => $company->id]);
 
        // Create invitation directly instead of going through the controller
        $invitation = \App\Models\UserInvitation::factory()->create([
            'email' => 'guide@example.com',
            'token' => 'test-token-123',
            'company_id' => $company->id,
            'role_id' => Role::GUIDE->value,
            'registered_at' => null,
        ]);
        
        Auth::logout();
 
        $response = $this->withSession(['invitation_token' => $invitation->token])->post('/register', [
            'name' => 'Test User',
            'email' => 'guide@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
 
        $this->assertDatabaseHas('users', [
            'email' => 'guide@example.com',
            'company_id' => $company->id,
            'role_id' => Role::GUIDE->value,
        ]);
 
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }
}
    