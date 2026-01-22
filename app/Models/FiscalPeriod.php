<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_closed',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function assetDepreciations(): HasMany
    {
        return $this->hasMany(AssetDepreciation::class);
    }

    public static function current()
    {
        return static::where('is_active', true)->first();
    }
}
