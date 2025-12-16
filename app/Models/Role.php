<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public const ADMINISTRATOR = 1;
    public const COMPANY_OWNER = 2;
    public const CUSTOMER = 3;
    public const GUIDE = 4;
    
}
