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
        return [
            'value' => 'decimal:2',
            'minimum_purchase' => 'decimal:2',
            'maximum_discount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isValid(float $subtotal): bool
    {
        return $this->invalidationReason($subtotal) === null;
    }

    public function invalidationReason(float $subtotal): ?string
    {
        if (! $this->is_active) {
            return 'Voucher is no longer active.';
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return 'Voucher is not yet active.';
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return 'Voucher has expired.';
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'Voucher usage limit has been reached.';
        }

        if ($subtotal < (float) $this->minimum_purchase) {
            return 'Minimum purchase for this voucher is Rp'.number_format((float) $this->minimum_purchase, 0, ',', '.').'.';
        }

        return null;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (! $this->isValid($subtotal)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ((float) $this->value / 100);
            if ($this->maximum_discount && $discount > (float) $this->maximum_discount) {
                $discount = (float) $this->maximum_discount;
            }

            return min($discount, $subtotal);
        }

        return min((float) $this->value, $subtotal);
    }
}
