<?php

namespace App\Models;

use App\Traits\BelongsToCommerce;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    use HasFactory, BelongsToCommerce;

    protected $fillable = [
        'commerce_id',
        'user_id',
        'cash_register_id',
        'customer_id',
        'total',
        'sale_date',
    ];

    /**
     * Productos incluidos en la venta (relación N:M).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sale')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    /**
     * Vendedor (usuario) que registró la venta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comercio al que pertenece la venta.
     */
    public function commerce(): BelongsTo
    {
        return $this->belongsTo(Commerce::class);
    }
}
