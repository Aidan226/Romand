<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'discount_percent', 'minimum_order', 'starts_at', 'ends_at', 'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /*
     * Promotion values come from the MySQL promotions table.
     * Supports both 5.00 (= 5%) and an older 0.05 (= 5%) value already
     * present in the database dump, without using config-based discounts.
     */
    public function getEffectiveDiscountPercentAttribute(): float
    {
        $value = (float) $this->discount_percent;
        return ($value > 0 && $value < 1) ? $value * 100 : $value;
    }

    public function scopeCurrentlyActive($query)
    {
        // starts_at / ends_at are DATETIME values entered by the admin in Myanmar time.
        $nowMyanmar = now('Asia/Yangon')->format('Y-m-d H:i:s');

        return $query->where('is_active', true)
            ->where(function ($q) use ($nowMyanmar) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $nowMyanmar);
            })
            ->where(function ($q) use ($nowMyanmar) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $nowMyanmar);
            });
    }
}
