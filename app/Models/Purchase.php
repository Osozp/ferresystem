<?php

namespace App\Models;

use App\Traits\BelongsToCommerce;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Purchase extends Model
{
    use HasFactory, BelongsToCommerce;

    protected $fillable = [
        'commerce_id',
        'provider_id',
        'purchase_date',
        'total',
        'status',
    ];

    /**
     * Productos incluidos en la compra (relación N:M).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_purchase')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Proveedor al que se le realizó la compra.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Comercio al que pertenece la compra.
     */
    public function commerce(): BelongsTo
    {
        return $this->belongsTo(Commerce::class);
    }
}
