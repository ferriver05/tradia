<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\Country;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'alias',
        'email',
        'password',
        'profile_picture',
        'bio',
        'city_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'reputation' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function exchangeRequests(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'requester_id');
    }
 
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function cities(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getFullLocationAttribute()
    {
        if (!$this->city_id) {
            return null;
        }

        $city = City::find($this->city_id);
        $state = $city?->state_id ? State::find($city->state_id) : null;
        $country = $city?->country_id ? Country::find($city->country_id) : null;

        $stateName = $state?->name;
        if ($stateName) {
            $stateName = str_replace([' Department', ' department', ' Departamento'], '', $stateName);
        }

        return collect([
            $city?->name,
            $stateName,
            $country?->name,
        ])->filter()->join(', ');
    }
}
