<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'debtor_id',
        'amount',
        'note',
        'adjusted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'adjusted_at' => 'datetime',
    ];

    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Debtor::class);
    }
}
