<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'fiscal_period_id',
        'business_unit_id',
        'total_amount',
        'status',
        'description',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTotalPlannedAttribute()
    {
        return $this->items()->sum('planned_amount');
    }

    public function getTotalRealizedAttribute()
    {
        return $this->items()->sum('realized_amount');
    }

    public function getTotalVarianceAttribute()
    {
        return $this->total_planned - $this->total_realized;
    }

    public function getRealizationPercentageAttribute()
    {
        if ($this->total_planned == 0) return 0;
        return round(($this->total_realized / $this->total_planned) * 100, 2);
    }
}
