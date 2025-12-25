<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Observers\ActivityObserver;
use Illuminate\Databse\Eloquent\Attributes\ObservedBy;

#[ObservedBy(ActivityObserver::class)] 
class Activity extends Model
{
     use HasFactory;
 
    protected $fillable = [
        'company_id',
        'guide_id',
        'name',
        'description',
        'start_time',
        'price',
        'thumbnail',
    ];
 
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
 
    public function participants()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value / 100,
            set: fn($value) => $value * 100,
        );
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? $value : '/no_image.jpg',
        );
    }

}
