<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    protected $fillable = [
        'budget_id',
        'account_id',
        'category_id',
        'description',
        'planned_amount',
        'realized_amount',
        'variance',
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'realized_amount' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function updateVariance(): void
    {
        $this->variance = $this->planned_amount - $this->realized_amount;
        $this->save();
    }

    public function getRealizationPercentageAttribute()
    {
        if ($this->planned_amount == 0) return 0;
        return round(($this->realized_amount / $this->planned_amount) * 100, 2);
    }

    public function isOverBudget(): bool
    {
        return $this->realized_amount > $this->planned_amount;
    }
}
