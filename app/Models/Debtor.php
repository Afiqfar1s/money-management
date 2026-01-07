<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debtor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'starting_outstanding',
        'outstanding',
        'debtor_type',
        'staff_number',
        'ic_number',
        'phone_number',
        'address',
        'position',
        'start_working_date',
        'resign_date',
        'ssm_number',
        'office_phone',
        'company_address',
    ];

    protected $casts = [
        'starting_outstanding' => 'decimal:2',
        'outstanding' => 'decimal:2',
        'start_working_date' => 'date',
        'resign_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function balanceAdjustments(): HasMany
    {
        return $this->hasMany(BalanceAdjustment::class);
    }
}
