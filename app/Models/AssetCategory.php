<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = [
        'name',
        'useful_life_years',
        'depreciation_rate',
        'depreciation_method',
        'account_id',
        'depreciation_account_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'useful_life_years' => 'integer',
        'depreciation_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function depreciationAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'depreciation_account_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
