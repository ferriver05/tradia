<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'item_condition',
        'exchange_preferences',
        'location',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const CONDITIONS = [
        'new' => 'Nuevo',
        'like_new' => 'Como nuevo',
        'used' => 'Usado',
        'damaged' => 'Dañado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ItemPhoto::class);
    }

    public function exchangeOffers(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'offered_item_id');
    }

    public function exchangeRequests(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'requested_item_id');
    }

    public function scopeBeingOffered($query)
    {
        return $query->whereHas('exchangeOffers', function ($q) {
            $q->where('status', 'pending');
        });
    }

    public function scopeBeingRequested($query)
    {
        return $query->whereHas('exchangeRequests', function ($q) {
            $q->where('status', 'pending');
        });
    }

    public function scopeInMatch($query)
    {
        return $query->whereHas('exchangeRequests', function ($q) {
            $q->where('status', 'accepted');
        })->orWhereHas('exchangeOffers', function ($q) {
            $q->where('status', 'accepted');
        });
    }

    public function isBeingOffered(): bool
    {
        return $this->exchangeOffers()->where('status', 'pending')->exists();
    }

    public function isBeingRequested(): bool
    {
        return $this->exchangeRequests()->where('status', 'pending')->exists();
    }

    public function hasMatchConfirmed(): bool
    {
        return $this->exchangeRequests()->where('status', 'accepted')->exists()
            || $this->exchangeOffers()->where('status', 'accepted')->exists();
    }

    public function visualStatus(): string
    {
        if ($this->status === 'exchanged') return 'intercambiado';
        if ($this->status === 'paused') return 'pausado';
        if ($this->hasMatchConfirmed()) return 'en_match';
        if ($this->isBeingRequested()) return 'solicitado';
        if ($this->isBeingOffered()) return 'ofrecido';
        return 'activo';
    }

    public function pause(): bool
    {
        if ($this->status === 'paused') {
            return false;
        }

        if ($this->status !== 'active') {
            return false;
        }

        $this->status = 'paused';
        return $this->save();
    }

    public function reactivate(): bool
    {
        if ($this->status === 'active') {
            return false;
        }

        if ($this->status !== 'paused') {
            return false;
        }

        $this->status = 'active';
        return $this->save();
    }

    public function isUserInMatch(User $user): bool
    {
        return $this->exchangeRequests()
            ->where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhereHas('offeredItem', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->orWhereHas('requestedItem', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->exists();
    }

}
