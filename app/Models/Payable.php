<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payable extends Model
{
    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'business_unit_id',
        'date',
        'due_date',
        'amount',
        'paid_amount',
        'remaining_amount',
        'description',
        'status',
        'attachment',
        'created_by',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PayablePayment::class);
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
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $last = static::where('invoice_number', 'like', "{$prefix}-{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return "{$prefix}-{$date}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function updateStatus(): void
    {
        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining_amount = $this->amount - $this->paid_amount;
        
        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date < now()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'unpaid';
        }
        
        $this->save();
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
    }
}
