<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'type', 'value', 'minimum_purchase', 'maximum_discount', 'usage_limit', 'usage_limit_per_user', 'used_count', 'starts_at', 'expires_at', 'is_active'])]
class Voucher extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['value' => 'decimal:2', 'minimum_purchase' => 'decimal:2', 'maximum_discount' => 'decimal:2', 'starts_at' => 'datetime', 'expires_at' => 'datetime', 'is_active' => 'boolean'];
    }
}
