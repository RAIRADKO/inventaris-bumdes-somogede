<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'normal_balance',
        'parent_id',
        'level',
        'is_header',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_header' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'account_id');
    }

    public function transactionCategories(): HasMany
    {
        return $this->hasMany(TransactionCategory::class, 'account_id');
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class, 'account_id');
    }

    // Get balance for this account
    public function getBalance($startDate = null, $endDate = null)
    {
        $query = $this->journalEntries()
            ->whereHas('journal', function ($q) {
                $q->where('status', 'approved');
            });

        if ($startDate) {
            $query->whereHas('journal', fn($q) => $q->where('date', '>=', $startDate));
        }
        if ($endDate) {
            $query->whereHas('journal', fn($q) => $q->where('date', '<=', $endDate));
        }

        $totals = $query->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')->first();

        if ($this->normal_balance === 'debit') {
            return ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0);
        } else {
            return ($totals->total_credit ?? 0) - ($totals->total_debit ?? 0);
        }
    }

    // Scope for active accounts
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for non-header accounts (can be used in transactions)
    public function scopePostable($query)
    {
        return $query->where('is_header', false)->where('is_active', true);
    }
}
