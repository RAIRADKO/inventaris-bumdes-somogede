<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapitalRecord extends Model
{
    protected $fillable = [
        'reference_number',
        'date',
        'type',
        'amount',
        'description',
        'contributor',
        'attachment',
        'created_by',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const TYPE_LABELS = [
        'initial_capital' => 'Modal Awal',
        'village_investment' => 'Penyertaan Modal Desa',
        'community_investment' => 'Penyertaan Modal Masyarakat',
        'retained_earnings' => 'Laba Ditahan',
        'dividend_distribution' => 'Pembagian SHU',
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
        $prefix = 'CAP';
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
}
