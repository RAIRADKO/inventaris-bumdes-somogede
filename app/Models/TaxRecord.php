<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRecord extends Model
{
    protected $fillable = [
        'reference_number',
        'date',
        'due_date',
        'type',
        'base_amount',
        'tax_amount',
        'description',
        'status',
        'payment_date',
        'payment_reference',
        'attachment',
        'created_by',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'base_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public const TYPE_LABELS = [
        'pph21' => 'PPh Pasal 21',
        'pph23' => 'PPh Pasal 23',
        'ppn' => 'PPN',
        'local_tax' => 'Pajak Daerah',
        'other' => 'Pajak Lainnya',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public static function generateNumber(): string
    {
        $prefix = 'TAX';
        $date = now()->format('Ymd');
        $last = static::where('reference_number', 'like', "{$prefix}-{$date}%")
            ->orderBy('reference_number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->reference_number, -4) + 1 : 1;
        return "{$prefix}-{$date}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
                    ->where('due_date', '<', now());
    }
}
