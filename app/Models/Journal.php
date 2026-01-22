<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    protected $fillable = [
        'journal_number',
        'date',
        'description',
        'business_unit_id',
        'fiscal_period_id',
        'status',
        'type',
        'reference_type',
        'reference_id',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Check if journal is balanced
    public function isBalanced(): bool
    {
        $totals = $this->entries()
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();

        return abs(($totals->total_debit ?? 0) - ($totals->total_credit ?? 0)) < 0.01;
    }

    // Generate journal number
    public static function generateNumber(): string
    {
        $prefix = 'JRN';
        $date = now()->format('Ymd');
        $lastJournal = static::where('journal_number', 'like', "{$prefix}-{$date}%")
            ->orderBy('journal_number', 'desc')
            ->first();

        if ($lastJournal) {
            $lastNumber = (int) substr($lastJournal->journal_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    public function getTotalDebitAttribute()
    {
        return $this->entries()->sum('debit');
    }

    public function getTotalCreditAttribute()
    {
        return $this->entries()->sum('credit');
    }
}
