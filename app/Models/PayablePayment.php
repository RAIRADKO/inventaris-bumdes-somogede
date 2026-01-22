<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayablePayment extends Model
{
    protected $fillable = [
        'payment_number',
        'payable_id',
        'date',
        'amount',
        'payment_method',
        'notes',
        'attachment',
        'created_by',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->payable->updateStatus();
        });

        static::deleted(function ($payment) {
            $payment->payable->updateStatus();
        });
    }

    public function payable(): BelongsTo
    {
        return $this->belongsTo(Payable::class);
    }

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
        $prefix = 'PYM';
        $date = now()->format('Ymd');
        $last = static::where('payment_number', 'like', "{$prefix}-{$date}%")
            ->orderBy('payment_number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->payment_number, -4) + 1 : 1;
        return "{$prefix}-{$date}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
