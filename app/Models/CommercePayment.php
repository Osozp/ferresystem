<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommercePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commerce_id',
        'amount',
        'days_added',
        'reference',
        'notes',
    ];

    public function commerce(): BelongsTo
    {
        return $this->belongsTo(Commerce::class);
    }
}
