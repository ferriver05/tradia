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
}
