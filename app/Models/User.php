<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Role as RoleEnum;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasFactory, Notifiable;
    use SoftDeletes;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'company_id'
    ];

    public function role(): BelongTo
    {
        return $this->belongsTo(Role::class);
    }
    public function company(): BelongTo
    {
        return $this->belongsTo(Company::class);
    }
    public function isAdmin(): bool
    {
        return $this->role_id === RoleEnum::ADMINISTRATOR->value;
    }

    public function isCompanyOwner(): bool
    {
        return $this->role_id === RoleEnum::COMPANY_OWNER->value;
    }

     

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}


// 1. Ek company banayein (agar nahi hai)
$company = App\Models\Company::firstOrCreate(['name' => 'My Test Company']);


$user = App\Models\User::where('email', 'owner@example.com')->first();
$user->company_id = $company->id;
$user->role_id = 2; 
$user->save();