<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeTransaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'date',
        'category_id',
        'business_unit_id',
        'amount',
        'description',
        'source',
        'reference',
        'attachment',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'category_id');
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public static function generateNumber(): string
    {
        $prefix = 'INC';
        $date = now()->format('Ymd');
        $last = static::where('transaction_number', 'like', "{$prefix}-{$date}%")
            ->orderBy('transaction_number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->transaction_number, -4) + 1 : 1;
        return "{$prefix}-{$date}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
