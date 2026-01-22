<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'business_unit_id',
        'acquisition_date',
        'acquisition_cost',
        'salvage_value',
        'current_value',
        'accumulated_depreciation',
        'condition',
        'location',
        'serial_number',
        'description',
        'photo',
        'status',
        'disposal_date',
        'disposal_value',
        'disposal_notes',
        'created_by',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'disposal_date' => 'date',
        'acquisition_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'disposal_value' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function depreciations(): HasMany
    {
        return $this->hasMany(AssetDepreciation::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateCode(AssetCategory $category): string
    {
        $prefix = 'AST-' . strtoupper(substr($category->name, 0, 3));
        $last = static::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        $number = $last ? (int) substr($last->code, -4) + 1 : 1;
        return "{$prefix}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Calculate monthly depreciation
    public function calculateDepreciation(): float
    {
        if ($this->status !== 'active') {
            return 0;
        }

        $category = $this->category;
        $depreciableAmount = $this->acquisition_cost - $this->salvage_value;

        if ($category->depreciation_method === 'straight_line') {
            return $depreciableAmount / ($category->useful_life_years * 12);
        } else {
            // Declining balance
            $remainingValue = $this->current_value - $this->salvage_value;
            return $remainingValue * ($category->depreciation_rate / 100 / 12);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getBookValueAttribute()
    {
        return $this->acquisition_cost - $this->accumulated_depreciation;
    }
}
