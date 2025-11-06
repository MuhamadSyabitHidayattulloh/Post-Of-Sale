<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'color', 'description', 'min_total', 'discount_percent', 'points_multiplier', 'is_active', 'sort_order'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'member_tier_id');
    }
}
