<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commerce extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    /**
     * Evalúa si el comercio tiene acceso permitido al sistema.
     */
    public function hasActiveAccess(): bool
    {
        // Si el estado es activo por pago al día
        if ($this->status === 'active') {
            return true;
        }

        // Si está en periodo de prueba y aún no vence
        if ($this->status === 'trialing' && $this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CommercePayment::class);
    }
}
