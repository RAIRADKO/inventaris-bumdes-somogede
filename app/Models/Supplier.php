<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'bank_name',
        'bank_account',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payables(): HasMany
    {
        return $this->hasMany(Payable::class);
    }

    public static function generateCode(): string
    {
        $prefix = 'SUPP';
        $last = static::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();

        $number = $last ? (int) substr($last->code, -4) + 1 : 1;
        return "{$prefix}" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalPayableAttribute()
    {
        return $this->payables()->where('status', '!=', 'paid')->sum('remaining_amount');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
