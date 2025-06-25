<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRequest extends Model
{
    use HasFactory;

    protected $table = 'exchange_requests';

    protected $fillable = [
        'requester_id',
        'requested_item_id',
        'offered_item_id',
        'status',
        'observations',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_by_requester' => 'boolean',
        'confirmed_by_owner' => 'boolean',
        'cancelled_by_requester' => 'boolean',
        'cancelled_by_owner' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function requestedItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'requested_item_id');
    }

    public function offeredItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'offered_item_id');
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }
}
