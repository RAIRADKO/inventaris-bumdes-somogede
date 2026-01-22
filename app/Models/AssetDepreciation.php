<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDepreciation extends Model
{
    protected $fillable = [
        'asset_id',
        'fiscal_period_id',
        'date',
        'amount',
        'accumulated_amount',
        'book_value',
        'journal_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'accumulated_amount' => 'decimal:2',
        'book_value' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }
}
