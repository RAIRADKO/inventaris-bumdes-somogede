<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalWorkflow extends Model
{
    protected $fillable = [
        'name',
        'module',
        'min_amount',
        'max_amount',
        'approval_levels',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'approval_levels' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getWorkflowFor(string $module, float $amount): ?self
    {
        return static::where('module', $module)
            ->where('is_active', true)
            ->where('min_amount', '<=', $amount)
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')
                  ->orWhere('max_amount', '>=', $amount);
            })
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
