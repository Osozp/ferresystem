<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    protected $fillable = [
        'commerce_id',
        'user_id',
        'opening_balance',
        'closing_balance',
        'expected_balance',
        'difference',
        'status',
        'notes',
        'opened_at',
        'closed_at',
    ];

    public function commerce(): BelongsTo
    {
        return $this->belongsTo(Commerce::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
