<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function receivables(): HasMany
    {
        return $this->hasMany(Receivable::class);
    }

    public static function generateCode(): string
    {
        $prefix = 'CUST';
        $last = static::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        $number = $last ? (int) substr($last->code, -4) + 1 : 1;
        return "{$prefix}" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalReceivableAttribute()
    {
        return $this->receivables()->where('status', '!=', 'paid')->sum('remaining_amount');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
